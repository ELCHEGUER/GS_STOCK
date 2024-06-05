<?php 


namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/data', name: 'users_data')]
    public function getData(Request $request, EntityManagerInterface $em, userRepository $userRepository): JsonResponse
    {
        $draw = intval($request->get('draw'));;
        $start = intval($request->get('start'));
        $length = intval($request->get('length'));
        $search = $request->get('search')['value'];
        $orderColumnIndex = intval($request->get('order')[0]['column']);
        $orderDirection = $request->get('order')[0]['dir'];
        $columns = $request->get('columns');
        $orderColumn = $columns[$orderColumnIndex]['data'];

        $queryBuilder = $userRepository->createQueryBuilder('m')->setFirstResult($start)->setMaxResults($length);

        if (!empty($orderColumn)) {
            $queryBuilder->orderBy("m.".$orderColumn, $orderDirection);
        }

        if (!empty($search)) {
            $queryBuilder->where('m.email LIKE :search OR m.roles LIKE :search')
                ->setParameter('search', "%".$search."%");
        }



        $totalRecords = $userRepository->count([]);

        $results = $queryBuilder->getQuery()->getResult();
        $formattedData = [];
        foreach ($results as $user) {
            $role = in_array('ROLE_ADMIN',$user->getRoles())?'ADMIN':'USER'; //pour eviter l'affichage de deux roles sur admin
            $formattedData[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $role,  //pour eviter l'affichage de deux roles sur admin
                'actions' => '<button class="btn btn-danger btn-sm" onclick="deleteUser('.$user->getId().')">Delete</button>',
            ];
        }
        return new JsonResponse([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => count($formattedData),
            'data' => $formattedData,
        ]);
    }

    #[Route('/add', name: 'user_add')]
public function add(Request $request, EntityManagerInterface $em): Response
{
    $user = new User(); // Create a new instance of the Model entity
    $form = $this->createForm(UserType::class, $user); // Create the form

    // Handle the form submission
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_user');
    }

    // Pass the form to the template
    return $this->render('user/add.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/delete/{id}', name: 'user_delete')]
    public function delete($id, EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
        return $this->redirectToRoute('app_user');
    }
}


