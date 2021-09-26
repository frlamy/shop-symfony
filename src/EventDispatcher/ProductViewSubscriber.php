<?php

namespace App\EventDispatcher;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Event\ProductViewEvent;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface
{

    protected $logger;
    protected $mailer;
    protected $security;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'productViewEmail'
        ];
    }

    public function productViewEmail(ProductViewEvent $productViewEvent)
    {
        /** @var User */
        $currentUser = $this->security->getUser();

        $email = new TemplatedEmail();
        $email->From(new Address("contact@symshop.com", "Infos de la boutique"))
            ->to("admin@symshop.com")
            ->text("Un visiteur est en train de voir la page du produit " .
                $productViewEvent->getProduct()->getId())
            ->htmlTemplate('emails/product_view.html.twig')
            ->context([
                'product' => $productViewEvent->getProduct(),
                'user' => $currentUser
            ])
            ->subject("Visite du produit " .
                $productViewEvent->getProduct()->getId());

        $this->mailer->send($email);

        $this->logger->info("Email envoyé à l'admin pour le produit " . $productViewEvent->getProduct()->getId());
    }
}
