<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;

class SetCreatedAtListener
{
    // Méthode appelée avant la persistance d'une entité
    public function prePersist(LifecycleEventArgs $args)
    {
        // Récupérer l'entité depuis les arguments de l'événement
        $entity = $args->getObject();

        // Si l'entité a une méthode 'getCreatedAt' et que 'createdAt' est null, définir 'createdAt' à la date et l'heure actuelles
        if (method_exists($entity, 'getCreatedAt') && is_null($entity->getCreatedAt())) {
            $entity->setCreatedAt(new \DateTimeImmutable());
        }

        // Si l'entité a une méthode 'getPaymentDate' et que 'paymentDate' est null, définir 'paymentDate' à la date et l'heure actuelles
        if (method_exists($entity, 'getPaymentDate') && is_null($entity->getPaymentDate())) {
            $entity->setPaymentDate(new \DateTimeImmutable());
        }

        // Si l'entité a une méthode 'getOrderDate' et que 'orderDate' est null, définir 'orderDate' à la date et l'heure actuelles
        if (method_exists($entity, 'getOrderDate') && is_null($entity->getOrderDate())) {
            $entity->setOrderDate(new \DateTimeImmutable());
        }
    }
}

?>