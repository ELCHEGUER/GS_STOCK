<?php 


namespace App\Controller;
use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/model')]
class ModelController extends AbstractController
{
    #[Route('/', name: 'app_model')]
    public function index(): Response
    {
        return $this->render('model/index.html.twig');
    }

    #[Route('/data', name: 'models_data')]
    public function getData(Request $request, EntityManagerInterface $em, ModelRepository $modelRepository): JsonResponse
    {
        $draw = intval($request->get('draw'));;
        $start = intval($request->get('start'));
        $length = intval($request->get('length'));
        $search = $request->get('search')['value'];
        $orderColumnIndex = intval($request->get('order')[0]['column']);
        $orderDirection = $request->get('order')[0]['dir'];
        $columns = $request->get('columns');
        $orderColumn = $columns[$orderColumnIndex]['data'];

        $queryBuilder = $modelRepository->createQueryBuilder('m')->setFirstResult($start)->setMaxResults($length);

        if (!empty($orderColumn)) {
            $queryBuilder->orderBy("m.".$orderColumn, $orderDirection);
        }

        if (!empty($search)) {
            $queryBuilder->where('m.name LIKE :search OR m.path LIKE :search OR m.icon LIKE :search OR m.roles LIKE :search')
                ->setParameter('search', "%".$search."%");
        }



        $totalRecords = $modelRepository->count([]);

        $results = $queryBuilder->getQuery()->getResult();
        $formattedData = [];
        foreach ($results as $model) {
            $formattedData[] = [
                'id' => $model->getId(),
                'name' => $model->getName(),
                'path' => $model->getPath(),
                'icon' => $model->getIcon(),
                'roles' => $model->getRoles(),
                'actions' => '<button class="btn btn-primary btn-sm" onclick="editModel('.$model->getId().')">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteModel('.$model->getId().')">Delete</button>',
            ];
        }
        return new JsonResponse([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => count($formattedData),
            'data' => $formattedData,
        ]);
    }

    #[Route('/add', name: 'model_add')]
public function add(Request $request, EntityManagerInterface $em): Response
{
    $model = new Model(); // Create a new instance of the Model entity
    $form = $this->createForm(ModelType::class, $model); // Create the form

    // Handle the form submission
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($model);
        $em->flush();

        return $this->redirectToRoute('app_model');
    }

    // Pass the form to the template
    return $this->render('model/add.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/edit/{id}', name: 'model_edit')]
    public function edit($id, Request $request, EntityManagerInterface $em, ModelRepository $modelRepository): Response
    {
        $model = $modelRepository->find($id);

        if (!$model) {
            throw $this->createNotFoundException('No model found for id '.$id);
        }

        $form = $this->createForm(ModelType::class, $model);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_model');
        }

        return $this->render('model/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'model_delete')]
    public function delete($id, EntityManagerInterface $em, ModelRepository $modelRepository): Response
    {
        $model = $modelRepository->find($id);
        if ($model) {
            $em->remove($model);
            $em->flush();
        }
        return $this->redirectToRoute('app_model');
    }
}


