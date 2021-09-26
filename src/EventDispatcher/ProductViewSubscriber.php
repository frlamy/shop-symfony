<?php

namespace App\EventDispatcher;

use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'productViewEmail'
        ];
    }

    public function productViewEmail(ProductViewEvent $productViewEvent)
    {
        $this->logger->info("Email envoyé à l'admin pour le produit " . $productViewEvent->getProduct()->getId());
    }
}
