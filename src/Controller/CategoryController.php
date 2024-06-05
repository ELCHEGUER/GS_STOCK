<?php 


namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig');
    }

    #[Route('/data', name: 'category_data')]
    public function getData(Request $request, EntityManagerInterface $em, CategoryRepository $categoryRepository): JsonResponse
    {
        $draw = intval($request->get('draw'));;
        $start = intval($request->get('start'));
        $length = intval($request->get('length'));
        $search = $request->get('search')['value'];
        $orderColumnIndex = intval($request->get('order')[0]['column']);
        $orderDirection = $request->get('order')[0]['dir'];
        $columns = $request->get('columns');
        $orderColumn = $columns[$orderColumnIndex]['data'];

        $queryBuilder = $categoryRepository->createQueryBuilder('m')->setFirstResult($start)->setMaxResults($length);

        if (!empty($orderColumn)) {
            $queryBuilder->orderBy("m.".$orderColumn, $orderDirection);
        }

        if (!empty($search)) {
            $queryBuilder->where('m.name LIKE :search OR m.description LIKE :search')
                ->setParameter('search', "%".$search."%");
        }



        $totalRecords = $categoryRepository->count([]);

        $results = $queryBuilder->getQuery()->getResult();
        $formattedData = [];
        foreach ($results as $category) {
            $formattedData[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'description' => $category->getDescription(),
                'actions' => '<button class="btn btn-primary btn-sm" onclick="editCategory('.$category->getId().')">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteCategory('.$category->getId().')">Delete</button>',
            ];
        }
        return new JsonResponse([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => count($formattedData),
            'data' => $formattedData,
        ]);
    }

    #[Route('/add', name: 'category_add')]
public function add(Request $request, EntityManagerInterface $em): Response 
{
    $category = new Category(); // Create a new instance of the Model entity
    $form = $this->createForm(CategoryType::class, $category); // Create the form

    // Handle the form submission
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($category);
        $em->flush();

        return $this->redirectToRoute('app_category');
    }

    // Pass the form to the template
    return $this->render('category/add.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/edit/{id}', name: 'category_edit')]
    public function edit($id, Request $request, EntityManagerInterface $em, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('No model found for id '.$id);
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'category_delete')]
    public function delete($id, EntityManagerInterface $em, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        if ($category) {
            $em->remove($category);
            $em->flush();
        }
        return $this->redirectToRoute('app_category');
    }
}


