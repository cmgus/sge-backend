<?php

namespace App\Entity;

use App\Repository\EvaluacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvaluacionRepository::class)
 */
class Evaluacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $calificacion;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity=SesionAprendizaje::class, inversedBy="evaluacions")
     */
    private $sesion;

    /**
     * @ORM\ManyToOne(targetEntity=Estudiante::class, inversedBy="evaluacions")
     */
    private $estudiante;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalificacion(): ?string
    {
        return $this->calificacion;
    }

    public function setCalificacion(string $calificacion): self
    {
        $this->calificacion = $calificacion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getSesion(): ?SesionAprendizaje
    {
        return $this->sesion;
    }

    public function setSesion(?SesionAprendizaje $sesion): self
    {
        $this->sesion = $sesion;

        return $this;
    }

    public function getEstudiante(): ?Estudiante
    {
        return $this->estudiante;
    }

    public function setEstudiante(?Estudiante $estudiante): self
    {
        $this->estudiante = $estudiante;

        return $this;
    }
}
