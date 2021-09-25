<?php

namespace App\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class PrenomSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'addPrenomToAttributes',
            'kernel.response' => 'test1'
        ];
    }

    public function addPrenomToAttributes(RequestEvent $requestEvent)
    {
        $requestEvent->getRequest()->attributes->set('prenom', 'Franck');
    }

    public function test1()
    {
        // dump('test 1');
    }

    public function test2()
    {
        // dump('test 2');
    }
}
