/* global Stripe */
const stripe = Stripe(window.stripePublicKey);
const elements = stripe.elements({wallets: { link: 'never' }});
const card = elements.create('card', { 
    disableLink: true, 
    style: {
    base: {
      fontFamily: "'Oxanium', sans-serif",
      fontSize: '16px',
      color: '#fff',
      '::placeholder': { color: '#aaa' },
    }
  }
});
card.mount('#card-element');

let clientSecret = null;

// Récupère le clientSecret du backend
fetch('/order/create-payment-intent', {method: 'POST'})
    .then(response => response.json())
    .then(data => {
        if (!data.error) {
            clientSecret = data.clientSecret;
        } else {
            document.getElementById('card-errors').textContent = data.error;
        }
    });

document.getElementById('payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const button = document.querySelector('#payment-form button[type="submit"]');
    if (button) button.disabled = true;

    const {error, paymentIntent} = await stripe.confirmCardPayment(clientSecret, {
        payment_method: { card: card }
    });

    if (error) {
        document.getElementById('card-errors').textContent = error.message;
        if (button) button.disabled = false;
    } else if (paymentIntent.status === 'succeeded') {
        fetch('/order/confirm', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                payment_method: 'stripe',
                stripe_payment_id: paymentIntent.id
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Commande enregistrée avec succès !');
                window.location.href = '/orders';
            } else {
                alert(data.message || 'Erreur lors de l\'enregistrement de la commande');
            }
        })
        .catch(() => {
            alert('Erreur serveur lors de la confirmation de commande');
        });
    }
});