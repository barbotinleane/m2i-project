<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationType extends AbstractType
{
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
