<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Patient;
use App\Entity\Appointment;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CreateAppointment.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsController]
final class CreateAppointment extends AbstractController
{
    public function __invoke(Appointment $appointment): Appointment
    {
        /** @var Patient $patient */
        $patient = $this->getUser();
        $appointment->setPatient($patient);
        return $appointment;
    }
}
