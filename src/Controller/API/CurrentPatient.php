<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Patient;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
final class CurrentPatient extends AbstractController
{
    public function __invoke(): Patient {
        /** @var Patient $patient */
        $patient = $this->getUser();

        return $patient;
    }
}
