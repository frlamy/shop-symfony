<?php

namespace App\Controller;

use Twig\Environment;
use App\Taxes\Calculator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    protected $logger;

    /**
     * @Route(
     *      "/hello/{name?World}",
     *      name="hello",
     *      methods={"GET", "POST"},
     *      host="localhost",
     *      schemes={"http", "https"})
     */
    public function hello(string $name, LoggerInterface $logger, Calculator $calculator, Environment $twig)
    {
        $logger->error("Mon message de $name");

        $tva = $calculator->calcul(98);

        dump($twig);
        dump($tva);

        return new Response("Hello $name");
    }
}
