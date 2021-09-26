<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Event\PurchaseSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Security;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    protected $mailer;
    protected $security;

    public function __construct(MailerInterface $mailer, Security $security)
    {
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        //1 . Récupérer l'utilisateur en ligne ->Security
        /** @var User */
        $currentUser = $this->security->getUser();
        //2 . Récupérer la commande ->PurchaseSuccessEvent
        $purchase = $purchaseSuccessEvent->getPurchase();

        //3 . Ecrire le mail
        $email = new TemplatedEmail();
        $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
            ->from("contact@symshop.com")
            ->subject("Votre commande n°{$purchase->getId()} a bien été confirmée")
            ->htmlTemplate('emails/purchase_success.html.twig')
            ->context([
                'user' => $currentUser,
                'purchase' => $purchase
            ]);
            
        //4 . Envoyer le mail
        $this->mailer->send($email);
    }
}
