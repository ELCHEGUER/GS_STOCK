<?php

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// class CartController extends AbstractController
// {
//     #[Route('user/cart', name: 'app_cart')]
//     public function index(): Response
//     {
//         return $this->render('cart/index.html.twig', [
//             'controller_name' => 'CartController',
//         ]);
//     }
// }


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class CartController extends AbstractController
{
    #[Route('user/cart', name: 'app_cart')]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart
        ]);
    }

    #[Route('user/cart/add/{id}', name: 'app_cart_add')]
    public function add(int $id, SessionInterface $session, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('The product does not exist');
        }

        $cart = $session->get('cart', []);
        if (!isset($cart[$id])) {
            $cart[$id] = [
                'product' => $product,
                'quantity' => 1
            ];
        } else {
            $cart[$id]['quantity']++;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }
    #[Route('user/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(int $id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $this->addFlash('success', 'Product removed from cart successfully.');
        } else {
            $this->addFlash('error', 'Product not found in cart.');
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    
}

