<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Patient;
use App\Entity\Appointment;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PatientAppointements.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsController]
final class PatientAppointements extends AbstractController
{
    /**
     * @return array<Appointment>
     */
    public function __invoke(): array
    {
        /** @var Patient $patient */
        $patient = $this->getUser();

        return $patient?->getAppointments()->toArray() ?? [];
    }
}
