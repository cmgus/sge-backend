<?php

namespace App\Entity;

use App\Repository\EstudianteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstudianteRepository::class)
 */
class Estudiante
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $codigo;

    /**
     * @ORM\ManyToOne(targetEntity=Persona::class, inversedBy="estudiantes")
     */
    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity=Docente::class, inversedBy="estudiantes")
     */
    private $docente;

    /**
     * @ORM\ManyToMany(targetEntity=Apoderado::class, mappedBy="estudiantes")
     */
    private $apoderados;

    /**
     * @ORM\OneToMany(targetEntity=Evaluacion::class, mappedBy="estudiante")
     */
    private $evaluacions;

    public function __construct()
    {
        $this->apoderados = new ArrayCollection();
        $this->evaluacions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

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

    public function getDocente(): ?Docente
    {
        return $this->docente;
    }

    public function setDocente(?Docente $docente): self
    {
        $this->docente = $docente;

        return $this;
    }

    /**
     * @return Collection|Apoderado[]
     */
    public function getApoderados(): Collection
    {
        return $this->apoderados;
    }

    public function addApoderado(Apoderado $apoderado): self
    {
        if (!$this->apoderados->contains($apoderado)) {
            $this->apoderados[] = $apoderado;
            $apoderado->addEstudiante($this);
        }

        return $this;
    }

    public function removeApoderado(Apoderado $apoderado): self
    {
        if ($this->apoderados->contains($apoderado)) {
            $this->apoderados->removeElement($apoderado);
            $apoderado->removeEstudiante($this);
        }

        return $this;
    }

    /**
     * @return Collection|Evaluacion[]
     */
    public function getEvaluacions(): Collection
    {
        return $this->evaluacions;
    }

    public function addEvaluacion(Evaluacion $evaluacion): self
    {
        if (!$this->evaluacions->contains($evaluacion)) {
            $this->evaluacions[] = $evaluacion;
            $evaluacion->setEstudiante($this);
        }

        return $this;
    }

    public function removeEvaluacion(Evaluacion $evaluacion): self
    {
        if ($this->evaluacions->contains($evaluacion)) {
            $this->evaluacions->removeElement($evaluacion);
            // set the owning side to null (unless already changed)
            if ($evaluacion->getEstudiante() === $this) {
                $evaluacion->setEstudiante(null);
            }
        }

        return $this;
    }
}
