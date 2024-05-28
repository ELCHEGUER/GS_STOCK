<?php 


namespace App\Controller;

use App\Entity\Model;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/model')]
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
                'actions' => 'test',
            ];
        }
        return new JsonResponse([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => count($formattedData),
            'data' => $formattedData,
        ]);
    }
}


