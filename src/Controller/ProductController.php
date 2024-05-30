<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_products')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig');
    }

    #[Route('/data', name: 'products_data')]
    public function getData(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $draw = intval($request->get('draw'));
        $start = intval($request->get('start'));
        $length = intval($request->get('length'));
        $search = $request->get('search')['value'];
        $orderColumnIndex = intval($request->get('order')[0]['column']);
        $orderDirection = $request->get('order')[0]['dir'];
        $columns = $request->get('columns');
        $orderColumn = $columns[$orderColumnIndex]['data'];

        $queryBuilder = $productRepository->createQueryBuilder('m')->setFirstResult($start)->setMaxResults($length);

        if (!empty($orderColumn)) {
            $queryBuilder->orderBy("m.".$orderColumn, $orderDirection);
        }

        if (!empty($search)) {
            $queryBuilder->where('m.name LIKE :search OR m.stock LIKE :search OR m.price LIKE :search')
                ->setParameter('search', "%".$search."%");
        }

        $totalRecords = $productRepository->count([]);
        $results = $queryBuilder->getQuery()->getResult();
        $formattedData = [];
        foreach ($results as $product) {
            $formattedData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'stock' => $product->getStock(),
                'price' => $product->getPrice(),
                'supplier' => $product->getSupplier() ? $product->getSupplier()->getName() : 'N/A',
                'image' => $product->getImage() ? '<img src="/uploads/images/'.$product->getImage().'" alt="'.$product->getName().'" width="50">' : '',
                'actions' => '<button class="btn btn-primary btn-sm" onclick="editProduct('.$product->getId().')">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteProduct('.$product->getId().')">Delete</button>',
            ];
        }
        return new JsonResponse([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => count($formattedData),
            'data' => $formattedData,
        ]);
    }

    #[Route('/add', name: 'product_add')]
public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
{
    $product = new Product();
    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imageFile')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle exception if something happens during file upload
            }

            $product->setImage($newFilename);
        }

        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('app_products');
    }

    return $this->render('product/add.html.twig', [
        'product' => $product,
        'form' => $form->createView(),
    ]);
}

#[Route('/edit/{id}', name: 'product_edit')]
public function edit($id, Request $request, EntityManagerInterface $em, ProductRepository $productRepository, SluggerInterface $slugger): Response
{
    $product = $productRepository->find($id);

    if (!$product) {
        throw $this->createNotFoundException('No product found for id '.$id);
    }

    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imageFile')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle exception if something happens during file upload
            }

            $product->setImage($newFilename);
        }

        $em->flush();

        return $this->redirectToRoute('app_products');
    }

    return $this->render('product/edit.html.twig', [
        'product' => $product,
        'form' => $form->createView(),
    ]);
}

    #[Route('/delete/{id}', name: 'product_delete')]
    public function delete($id, EntityManagerInterface $em, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if ($product) {
            $em->remove($product);
            $em->flush();
        }
        return $this->redirectToRoute('app_products');
    }
}
