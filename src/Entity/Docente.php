<?php

namespace App\Entity;

use App\Repository\DocenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocenteRepository::class)
 */
class Docente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $incio;

    /**
     * @ORM\Column(type="date")
     */
    private $fin;

    /**
     * @ORM\ManyToOne(targetEntity=Persona::class, inversedBy="docentes")
     */
    private $persona;

    /**
     * @ORM\OneToMany(targetEntity=Estudiante::class, mappedBy="docente")
     */
    private $estudiantes;

    /**
     * @ORM\OneToMany(targetEntity=SesionAprendizaje::class, mappedBy="docente")
     */
    private $sesionAprendizajes;

    public function __construct()
    {
        $this->estudiantes = new ArrayCollection();
        $this->sesionAprendizajes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIncio(): ?\DateTimeInterface
    {
        return $this->incio;
    }

    public function setIncio(\DateTimeInterface $incio): self
    {
        $this->incio = $incio;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
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
            $estudiante->setDocente($this);
        }

        return $this;
    }

    public function removeEstudiante(Estudiante $estudiante): self
    {
        if ($this->estudiantes->contains($estudiante)) {
            $this->estudiantes->removeElement($estudiante);
            // set the owning side to null (unless already changed)
            if ($estudiante->getDocente() === $this) {
                $estudiante->setDocente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SesionAprendizaje[]
     */
    public function getSesionAprendizajes(): Collection
    {
        return $this->sesionAprendizajes;
    }

    public function addSesionAprendizaje(SesionAprendizaje $sesionAprendizaje): self
    {
        if (!$this->sesionAprendizajes->contains($sesionAprendizaje)) {
            $this->sesionAprendizajes[] = $sesionAprendizaje;
            $sesionAprendizaje->setDocente($this);
        }

        return $this;
    }

    public function removeSesionAprendizaje(SesionAprendizaje $sesionAprendizaje): self
    {
        if ($this->sesionAprendizajes->contains($sesionAprendizaje)) {
            $this->sesionAprendizajes->removeElement($sesionAprendizaje);
            // set the owning side to null (unless already changed)
            if ($sesionAprendizaje->getDocente() === $this) {
                $sesionAprendizaje->setDocente(null);
            }
        }

        return $this;
    }
}
