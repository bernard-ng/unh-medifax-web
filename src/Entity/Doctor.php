<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use App\Repository\DoctorRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: DoctorRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['read:item']]),
        new GetCollection(normalizationContext: ['groups' => ['read:collection']])
    ]
)]
class Doctor extends User
{
    #[ORM\ManyToOne(inversedBy: 'doctors')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:collection', 'read:item'])]
    private ?Specialization $specialization = null;

    #[ORM\Column]
    #[Groups(['read:collection', 'read:item'])]
    private ?bool $is_available = null;

    #[ORM\OneToMany(mappedBy: 'doctor', targetEntity: Appointment::class, orphanRemoval: true)]
    private Collection $appointments;

    public function __construct()
    {
        parent::__construct();
        $this->roles = ['ROLE_USER', 'ROLE_DOCTOR'];
        $this->appointments = new ArrayCollection();
    }

    public function getSpecialization(): ?Specialization
    {
        return $this->specialization;
    }

    public function setSpecialization(?Specialization $specialization): static
    {
        $this->specialization = $specialization;

        return $this;
    }

    public function isIsAvailable(): ?bool
    {
        return $this->is_available;
    }

    public function setIsAvailable(bool $is_available): static
    {
        $this->is_available = $is_available;

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
            $appointment->setDoctor($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getDoctor() === $this) {
                $appointment->setDoctor(null);
            }
        }

        return $this;
    }
}
