<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationType extends AbstractType
{

    public function __construct(private ReservationRepository $reservationRepository, private LoggerInterface $logger)
    {
    }

    /**
     * Builds the form for creating or editing a reservation.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array $options The options for the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerName', TextType::class, [
                'label' => 'Nom complet'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('reservationDate', DateTimeType::class, [
                'label' => 'Date et heure de réservation',
                'widget' => 'single_text',
            ])
            ->add('numberOfGuests', IntegerType::class, [
                'label' => 'Nombre de couverts',
            ])
            ->add('comment', TextType::class, [
                'label' => 'Commentaire (facultatif)',
                'required' => false,
            ]);
        
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            if (!isset($data['reservationDate'], $data['numberOfGuests'], $data['email'])) {
                return;
            }

            $date = new \DateTime($data['reservationDate']);
            $requestedGuests = (int) $data['numberOfGuests'];

            /** @var ReservationRepository $repo */
            $repo = $this->reservationRepository; // passe-le par le constructeur ou les options si besoin
            $totalGuests = $repo->getTotalGuestsForDate($date) + $requestedGuests;

            if ($totalGuests > 20) {
                $this->logger->error('Tentative de réservation dépassant la capacité maximale.');
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
