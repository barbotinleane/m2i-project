<?php

namespace App\Controller;

use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ReservationType;
use Symfony\Component\HttpFoundation\Request;

final class ReservationController extends AbstractController
{
    #[Route(path: '/reservations', name: 'app_reservation')]
    public function reservations(Request $request): Response
    {
        $reservation = new Reservation();
        
        $session = $request->getSession();
        if (!$session->has('reservations')) {
            $session->set('reservations', []);
        }
        
        $form = $this->createForm(ReservationType::class, $reservation);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            
            $this->addFlash('success', 'Votre réservation a été enregistrée avec succès !');
        }

        return $this->render('reservation/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
