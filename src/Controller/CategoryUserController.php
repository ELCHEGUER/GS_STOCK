<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryUserController extends AbstractController
{
    #[Route('user/category', name: 'app_category_user')]
    public function index(): Response
    {
        return $this->render('category_user/index.html.twig', [
            'controller_name' => 'CategoryUserController',
        ]);
    }
}
