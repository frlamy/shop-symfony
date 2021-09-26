<?php

namespace App\Purchase;

use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Service\CartService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister
{
    protected $security;
    protected $cartService;
    protected $em;

    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;
    }

    public function storePurchase(Purchase $purchase)
    {
        //5. Créer une Purchase, la lier avec l'utilisateur connecté -> Security
        /** @var Purchase */
        $purchase->setUser($this->security->getUser())
            ->setTotal($this->cartService->getTotal());

        $this->em->persist($purchase);

        //6. la lier avec les produits dans le panier
        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setQuantity($cartItem->qty)
                ->setProductPrice($cartItem->product->getPrice())
                ->setTotal($cartItem->getTotal());

            $this->em->persist($purchaseItem);
        }

        //7. Enregistrer la commande -> EntityManagerInterface
        $this->em->flush();
    }
}
