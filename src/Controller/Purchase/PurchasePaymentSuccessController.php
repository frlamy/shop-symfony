<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Event\PurchaseSuccessEvent;
use App\Service\CartService;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PurchasePaymentSuccessController extends AbstractController
{
    /**
     * @Route("/purchase/success/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService, EventDispatcherInterface $dispatcher)
    {
        //1. Je récupère la commande
        /** @var Purchase */
        $purchase = $purchaseRepository->find($id);

        if (
            !$purchase
            || ($purchase && $purchase->getUser() !== $this->getUser())
            || ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)
        ) {
            $this->addFlash('warning', 'La commande n\'existe pas');
            return $this->redirectToRoute("purchase_index");
        }

        //2. Elle est payée
        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();

        //3. Panier vidé
        $cartService->empty();

        //Emettre un évènement pour réagir au paiement d'une commande
        $purchaseEvent = new PurchaseSuccessEvent($purchase);

        $dispatcher->dispatch($purchaseEvent, 'purchase.success');

        //4. Redirection avec flash vers la liste des commandes
        $this->addFlash('success', "La commande a été payée et confirmée");
        return $this->redirectToRoute("purchase_index");
    }
}
