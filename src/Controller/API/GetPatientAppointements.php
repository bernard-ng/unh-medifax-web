<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Patient;
use App\Entity\Appointment;
use App\Repository\PatientRepository;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class GetPatientAppointements.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsController]
final readonly class GetPatientAppointements
{
    public function __construct(
       private PatientRepository $repository
    ) {
    }

    /**
     * @return array<Appointment>
     */
    public function __invoke(string $id): array
    {
        /** @var Patient|null $patient */
        $patient = $this->repository->find($id);

        return $patient?->getAppointments()->toArray();
    }
}
