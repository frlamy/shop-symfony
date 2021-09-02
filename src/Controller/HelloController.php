<?php

namespace App\Controller;

use Twig\Environment;
use App\Taxes\Detector;
use App\Taxes\Calculator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{
    /**
     * @Route(
     *      "/hello/{name?World}",
     *      name="hello",
     *      methods={"GET", "POST"},
     *      host="localhost",
     *      schemes={"http", "https"})
     */
    public function hello()
    {
        return $this->render('hello.html.twig', [
            'prenom' => 'Franck',
            'formateur1' => [
                'prenom' => 'Benoit',
                'nom' => 'Dupuis'
            ],
            'formateur2' => [
                'prenom' => 'Bertrand',
                'nom' => 'Doukouré'
            ]
        ]);
    }
    /**
     * @Route("/example", name="example")
     */
    public function example()
    {
        return $this->render('example.html.twig', [
            'age' => 33
        ]);
    }
}
