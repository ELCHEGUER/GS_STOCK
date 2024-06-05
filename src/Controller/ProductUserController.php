<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class ProductUserController extends AbstractController
{
    #[Route('user/ProductsUser', name: 'app_product_user')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product_user/index.html.twig', [ 
            'products' => $products,
        ]);
    }
}
