<?php

namespace App\Controller;

use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class ReservationController extends AbstractController
{
    #[Route(path: '/reservations', name: 'app_reservation')]
    public function reservations(Request $request, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $reservations = $reservationRepository->findUpcomingReservations();

        $form = $this->createForm(ReservationType::class, $reservation);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($reservationRepository->getTotalGuestsForDate($reservation->getReservationDate()) + $reservation->getNumberOfGuests()) > 20) {
                $this->addFlash('error', 'La capacité maximale pour cette date a été atteinte.');
                return $this->redirectToRoute('app_reservation');
            }

            $entityManager->persist($reservation);
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre réservation a été enregistrée avec succès !');
        }

        return $this->render('reservation/index.html.twig', [
            'form' => $form->createView(),
            'reservations' => $reservations,
        ]);
    }
}
