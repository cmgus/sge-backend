<?php

namespace App\Entity;

use App\Repository\SesionAprendizajeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SesionAprendizajeRepository::class)
 */
class SesionAprendizaje
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=Curso::class, inversedBy="sesionAprendizajes")
     */
    private $curso;

    /**
     * @ORM\ManyToOne(targetEntity=Docente::class, inversedBy="sesionAprendizajes")
     */
    private $docente;

    /**
     * @ORM\OneToMany(targetEntity=Evaluacion::class, mappedBy="sesion")
     */
    private $evaluacions;

    public function __construct()
    {
        $this->evaluacions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): self
    {
        $this->curso = $curso;

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
            $evaluacion->setSesion($this);
        }

        return $this;
    }

    public function removeEvaluacion(Evaluacion $evaluacion): self
    {
        if ($this->evaluacions->contains($evaluacion)) {
            $this->evaluacions->removeElement($evaluacion);
            // set the owning side to null (unless already changed)
            if ($evaluacion->getSesion() === $this) {
                $evaluacion->setSesion(null);
            }
        }

        return $this;
    }
}
