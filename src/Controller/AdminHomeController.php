<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Repository\SupplierRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderRepository; // Import the OrderRepository

class AdminHomeController extends AbstractController
{
    private $userRepository;
    private $supplierRepository;
    private $categoryRepository;
    private $orderRepository;

    public function __construct(UserRepository $userRepository, SupplierRepository $supplierRepository, CategoryRepository $categoryRepository, OrderRepository $orderRepository)
    {
        $this->userRepository = $userRepository;
        $this->supplierRepository = $supplierRepository;
        $this->categoryRepository = $categoryRepository;
        $this->orderRepository = $orderRepository;
    }

    #[Route('/home', name: 'app_admin_home')]
    public function index(): Response
    {
        // Fetch the total number of users
        $totalUsers = $this->userRepository->count([]);

        // Fetch the total number of suppliers
        $totalSuppliers = $this->supplierRepository->count([]);

        // Fetch the total number of category products
        $totalCategoryProducts = $this->categoryRepository->count([]);

        // Fetch the total number of orders
        $totalOrders = $this->orderRepository->count([]);

        return $this->render('admin_home/index.html.twig', [
            'controller_name' => 'AdminHomeController',
            'totalUsers' => $totalUsers,
            'totalSuppliers' => $totalSuppliers,
            'totalCategoryProducts' => $totalCategoryProducts,
            'totalOrders' => $totalOrders, // Pass the totalOrders variable to the template
            
        ]);
    }
}