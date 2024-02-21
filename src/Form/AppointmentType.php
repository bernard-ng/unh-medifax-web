<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Enum\AppointmentStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class AppointmentType.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'email',
            ])
            ->add('doctor', EntityType::class, [
                'class' => Doctor::class,
                'choice_label' => 'email',
            ])
            ->add('status', EnumType::class, [
                'class' => AppointmentStatus::class
            ])
            ->add('description', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
