<?php

namespace App\Controller\Admin;

use App\Entity\Appointment;
use App\Enum\AppointmentStatus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AppointmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Appointment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Appointment')
            ->setEntityLabelInPlural('Appontements');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('doctor')->autocomplete(),
            AssociationField::new('patient')->autocomplete(),
            DateTimeField::new('createdAt'),
            TextEditorField::new('description'),
            ChoiceField::new('status')
                ->setChoices(AppointmentStatus::cases())
                ->setValue(AppointmentStatus::PENDING)
                ->renderExpanded()
        ];
    }
}
