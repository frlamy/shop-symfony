<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasesListController extends AbstractController
{

    /**
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à vos commandes")
     */
    public function index()
    {
        // 1. Nous devons nous assurer que la personne est connectée sinon redirection (accueil) -> Security
        // 2. Nous voulons savoir qui est connecté -> Security
        /** @var User */
        $user = $this->getUser();

        // 3. Nous voulons passer l'utilisateur à twig afin d'afficher d'afficher ses commandes -> Environment / Response
        return $this->render("purchase/index.html.twig", [
            'purchases' => $user->getPurchases()
        ]);
    }
}
