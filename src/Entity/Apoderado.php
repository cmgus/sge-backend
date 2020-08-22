<?php

namespace App\Entity;

use App\Repository\ApoderadoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApoderadoRepository::class)
 */
class Apoderado
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Persona::class, inversedBy="apoderados")
     */
    private $persona;

    /**
     * @ORM\ManyToMany(targetEntity=Estudiante::class, inversedBy="apoderados")
     */
    private $estudiantes;

    public function __construct()
    {
        $this->estudiantes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * @return Collection|Estudiante[]
     */
    public function getEstudiantes(): Collection
    {
        return $this->estudiantes;
    }

    public function addEstudiante(Estudiante $estudiante): self
    {
        if (!$this->estudiantes->contains($estudiante)) {
            $this->estudiantes[] = $estudiante;
        }

        return $this;
    }

    public function removeEstudiante(Estudiante $estudiante): self
    {
        if ($this->estudiantes->contains($estudiante)) {
            $this->estudiantes->removeElement($estudiante);
        }

        return $this;
    }
}
