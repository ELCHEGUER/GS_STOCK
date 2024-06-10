<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\Payement;
use App\Form\PayementType;
use App\Repository\PayementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PayementController extends AbstractController
{
    #[Route('/payement/new', name: 'payement_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        // Calculate the grand total
        $grand_total = 0;
        foreach ($cart as $item) {
            $grand_total += $item['product']->getPrice() * $item['quantity'];
        }
        
        $payement = new Payement();
        $payement->setPayementDate(new \DateTime());
        $payement->setAmount($grand_total);

        $form = $this->createForm(PayementType::class, $payement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Store the data into the database
            $entityManager->persist($payement);
            $entityManager->flush();

            // Add flash message
            $this->addFlash('success', 'Payment has been successfully submitted.');

            // Redirect after form submission
            return $this->redirectToRoute('payement_success');
        }

        return $this->render('payement/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/payement/success", name: "payement_success", methods: ["GET"])]
    public function payementSuccess(): Response
    {
        return $this->render('payement/success.html.twig');
    }

    #[Route("/payement_success_pdf", name: "payement_success_pdf", methods: ["GET"])]
    public function generateSuccessPDF(PayementRepository $payementRepository): Response
    {
        // Fetch the latest payment or any specific payment record
        $payement = $payementRepository->findOneBy([], ['id' => 'DESC']);

        if (!$payement) {
            throw $this->createNotFoundException('No payment found.');
        }

        // Generate PDF
        $dompdf = new Dompdf();
        $html = $this->renderView('payement/pdf_template.html.twig', [
            'payement' => $payement,
            
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="payment_success.pdf"'
            ]
        );
    }


    #[Route("/payement_success_pdf_mini", name: "payement_success_pdf_mini", methods: ["GET"])]
    public function generateSuccessPDFF(PayementRepository $payementRepository): Response
    {
        // Fetch the latest payment or any specific payment record
        $payement = $payementRepository->findOneBy([], ['id' => 'DESC']);

        if (!$payement) {
            throw $this->createNotFoundException('No payment found.');
        }

        // Generate PDF
        $dompdf = new Dompdf();
        $html = $this->renderView('payement/pdfmini_template.html.twig', [
            'payement' => $payement,
            
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A6', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="payment_success.pdf"'
            ]
        );
    }

}
