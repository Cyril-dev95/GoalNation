<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Orders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrdersController extends AbstractController
{
    #[Route('/orders', name: 'app_orders')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $orders = $entityManager->getRepository(\App\Entity\Orders::class)
            ->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('orders/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/order/payment', name: 'order_payment')]
    public function payment(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $this->render('orders/payment.html.twig', [
            'total' => $total,
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY'],
        ]);
    }

    #[Route('/order/create-payment-intent', name: 'order_create_payment_intent', methods: ['POST'])]
    public function createPaymentIntent(SessionInterface $session): JsonResponse
    {
        try {
            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

            $cart = $session->get('cart', []);
            if (empty($cart)) {
                return new JsonResponse(['error' => 'Panier vide'], 400);
            }
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['product']->getPrice() * $item['quantity'];
            }

            $intent = \Stripe\PaymentIntent::create([
                'amount' => (int) round($total * 100),
                'currency' => 'eur',
            ]);

            return new JsonResponse(['clientSecret' => $intent->client_secret]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/order/confirm', name: 'order_confirm', methods: ['POST'])]
    public function confirmOrder(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        $cart = $session->get('cart', []);
        $data = json_decode($request->getContent(), true);
        $paymentMethod = $data['payment_method'] ?? 'stripe';
        $stripePaymentId = $data['stripe_payment_id'] ?? null;

        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non connecté'], 403);
        }

        if (empty($cart)) {
            return new JsonResponse(['error' => 'Panier vide'], 400);
        }

        $order = new Orders();
        $order->setUser($user);
        $order->setStatus('Paid');
        $order->setPaymentMethod($paymentMethod);
        $order->setShippingStatus('Processing');

        $totalPrice = 0;

        foreach ($cart as $item) {
            $productId = is_object($item['product']) ? $item['product']->getId() : $item['product'];
            $product = $entityManager->getRepository(\App\Entity\Products::class)->find($productId);

            if (!$product) {
                return new JsonResponse(['error' => 'Produit introuvable'], 400);
            }

            $quantity = $item['quantity'];

            $orderDetail = new \App\Entity\OrderDetails();
            $orderDetail->setOrders($order);
            $orderDetail->setProduct($product);
            $orderDetail->setQuantity($quantity);
            $orderDetail->setTotalPrice($product->getPrice() * $quantity);

            $order->addOrderDetail($orderDetail);

            $totalPrice += $product->getPrice() * $quantity;
        }

        $order->setTotal($totalPrice);
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setOrderDate(new \DateTime());

        $entityManager->persist($order);
        $entityManager->flush();

        if ($stripePaymentId) {
            try {
                Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
                $paymentIntent = \Stripe\PaymentIntent::retrieve($stripePaymentId);

                if (!$paymentIntent) {
                    return new JsonResponse(['error' => 'PaymentIntent introuvable'], 400);
                }

                $payment = new \App\Entity\Payments();
                $payment->setStripePaymentId($paymentIntent->id ?? null);
                $payment->setAmount($paymentIntent->amount_received / 100);
                $payment->setCurrency($paymentIntent->currency);
                $payment->setStatus($paymentIntent->status);
                $payment->setPaymentMethod($paymentIntent->payment_method_types[0] ?? 'stripe');
                $payment->setPaymentDate((new \DateTimeImmutable())->setTimestamp($paymentIntent->created));
                $payment->setOrder($order);

                $entityManager->persist($payment);
                $entityManager->flush();
            } catch (\Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], 500);
            }
        }

        $session->set('cart', []);

        return new JsonResponse(['success' => true, 'message' => 'Commande enregistrée avec succès']);
    }
}