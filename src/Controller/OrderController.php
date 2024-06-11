<?php
namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/user/order', name: 'app_order')]
    public function index(Request $request, EntityManagerInterface $em, ProductRepository $productRepository, SessionInterface $session): Response
    {
        // Retrieve the cart from the session
        $cart = $session->get('cart', []);

        // Calculate the grand total
        $grand_total = 0;
        foreach ($cart as $item) {
            $grand_total += $item['product']->getPrice() * $item['quantity'];
        }

        // Create a new Order entity and set the total amount
        $order = new Order();
        $order->setTotalAmount($grand_total);
        $order->setOrderDate(new \DateTime());
        $order->setPayementStatus('Pending');
        $order->setUser($this->getUser()); // Assuming you have user authentication

        // Create the form and pass the order entity
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Process the cart items
            foreach ($cart as $item) {
                $product = $productRepository->find($item['product']->getId());
                if ($product) {
                    $orderItem = new OrderItem();
                    $orderItem->setProduct($product);
                    $orderItem->setQuantity($item['quantity']);
                    $orderItem->setPrice($product->getPrice() * $item['quantity']);
                    $order->addOrderItem($orderItem);
                    // Explicitly persist each OrderItem
                    $em->persist($orderItem);
                }
            }

            $em->persist($order);
            $em->flush();

            // Clear the cart
            $session->remove('cart');

            // Add a success flash message
            $this->addFlash('success', 'Order placed successfully.');



            $session->set('amount', $grand_total);


            
            // Redirect to a success page or order summary page
            return $this->redirectToRoute('payement_new');
        }

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),

        ]);
        
    }

    #[Route("/order/success", name: "order_success", methods: ["GET"])]
    public function orderSuccess(): Response
    {
        return $this->render('order/success.html.twig');
    }
}

