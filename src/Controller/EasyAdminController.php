<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EasyAdminController extends AbstractController
{
    /**
     * @Route("/easy/admin", name="easy_admin")
     */
    public function index()
    {
        return $this->render('easy_admin/index.html.twig', [
            'controller_name' => 'EasyAdminController',
        ]);
    }
}
