<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\SpecializationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpecializationRepository::class)]
class Specialization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection', 'read:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read:collection', 'read:item'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read:collection', 'read:item'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'specialization', targetEntity: Doctor::class)]
    private Collection $doctors;

    public function __construct()
    {
        $this->doctors = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Doctor>
     */
    public function getDoctors(): Collection
    {
        return $this->doctors;
    }

    public function addDoctor(Doctor $doctor): static
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors->add($doctor);
            $doctor->setSpecialization($this);
        }

        return $this;
    }

    public function removeDoctor(Doctor $doctor): static
    {
        if ($this->doctors->removeElement($doctor)) {
            // set the owning side to null (unless already changed)
            if ($doctor->getSpecialization() === $this) {
                $doctor->setSpecialization(null);
            }
        }

        return $this;
    }
}
