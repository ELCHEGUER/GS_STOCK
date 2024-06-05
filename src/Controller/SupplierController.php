<?php 


namespace App\Controller;
use App\Entity\Supplier;
use App\Form\SupplierType;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/supplier')] 
class SupplierController extends AbstractController
{
    #[Route('/', name: 'app_supplier')]
    public function index(): Response
    {
        return $this->render('supplier/index.html.twig');
    }

    #[Route('/data', name: 'supplier_data')]
    public function getData(Request $request, EntityManagerInterface $em, SupplierRepository $supplierRepository): JsonResponse
    {
        $draw = intval($request->get('draw'));;
        $start = intval($request->get('start'));
        $length = intval($request->get('length'));
        $search = $request->get('search')['value'];
        $orderColumnIndex = intval($request->get('order')[0]['column']);
        $orderDirection = $request->get('order')[0]['dir'];
        $columns = $request->get('columns');
        $orderColumn = $columns[$orderColumnIndex]['data'];

        $queryBuilder = $supplierRepository->createQueryBuilder('m')->setFirstResult($start)->setMaxResults($length);

        if (!empty($orderColumn)) {
            $queryBuilder->orderBy("m.".$orderColumn, $orderDirection);
        }

        if (!empty($search)) {
            $queryBuilder->where('m.name LIKE :search OR m.email LIKE :search OR m.numero LIKE :search OR m.adress LIKE :search OR m.cin LIKE :search')
                ->setParameter('search', "%".$search."%");
        }



        $totalRecords = $supplierRepository->count([]);

        $results = $queryBuilder->getQuery()->getResult();
        $formattedData = [];
        foreach ($results as $supplier) {
            $formattedData[] = [
                'id' => $supplier->getId(),
                'name' => $supplier->getName(),
                'email' => $supplier->getEmail(),
                'numero' => $supplier->getNumero(),
                'adress' => $supplier->getAdress(),
                'cin' => $supplier->getCIN(),
                'actions' => '<button class="btn btn-primary btn-sm" onclick="editSupplier('.$supplier->getId().')">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteSupplier('.$supplier->getId().')">Delete</button>',
            ];
        }
        return new JsonResponse([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => count($formattedData),
            'data' => $formattedData,
        ]);
    }

    #[Route('/add', name: 'supplier_add')]
public function add(Request $request, EntityManagerInterface $em): Response
{
    $supplier = new Supplier(); // Create a new instance of the Model entity
    $form = $this->createForm(SupplierType::class, $supplier); // Create the form

    // Handle the form submission
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($supplier);
        $em->flush();

        return $this->redirectToRoute('app_supplier');
    }

    // Pass the form to the template
    return $this->render('supplier/add.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/edit/{id}', name: 'supplier_edit')]
    public function edit($id, Request $request, EntityManagerInterface $em, SupplierRepository $supplierRepository): Response
    {
        $supplier = $supplierRepository->find($id);

        if (!$supplier) {
            throw $this->createNotFoundException('No model found for id '.$id);
        }

        $form = $this->createForm(SupplierType::class, $supplier);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_supplier');
        }

        return $this->render('supplier/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'supplier_delete')]
    public function delete($id, EntityManagerInterface $em, SupplierRepository $supplierRepository): Response
    {
        $supplier = $supplierRepository->find($id);
        if ($supplier) {
            $em->remove($supplier);
            $em->flush();
        }
        return $this->redirectToRoute('app_supplier');
    }
}


