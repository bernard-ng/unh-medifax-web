<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Subscription;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\PatientRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\API\RegisterPatient;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Controller\API\PatientAppointements;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['read:item']]),
        new Post(
            uriTemplate: '/register',
            controller: RegisterPatient::class,
            openapiContext: [
                'summary' => 'Registers a new patient'
            ],
            normalizationContext: ['groups' => ['read:item']],
            denormalizationContext: ['groups' => ['write:item']],
            write: false,
            name: 'register'
        )
    ]
)]
class Patient extends User
{
    #[ORM\Column(type: 'string', enumType: Subscription::class)]
    #[Groups(['read:collection', 'read:item'])]
    private ?Subscription $subscription = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Appointment::class, orphanRemoval: true)]
    private Collection $appointments;

    public function __construct()
    {
        parent::__construct();
        $this->roles = ['ROLE_USER', 'ROLE_PATIENT'];
        $this->appointments = new ArrayCollection();
        $this->subscription = Subscription::FREE;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(Subscription $subscription): static
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setPatient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getPatient() === $this) {
                $appointment->setPatient(null);
            }
        }

        return $this;
    }
}
