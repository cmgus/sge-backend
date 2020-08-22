<?php

namespace App\Entity;

use App\Repository\CursoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CursoRepository::class)
 */
class Curso
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=SesionAprendizaje::class, mappedBy="curso")
     */
    private $sesionAprendizajes;

    public function __construct()
    {
        $this->sesionAprendizajes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

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
            $sesionAprendizaje->setCurso($this);
        }

        return $this;
    }

    public function removeSesionAprendizaje(SesionAprendizaje $sesionAprendizaje): self
    {
        if ($this->sesionAprendizajes->contains($sesionAprendizaje)) {
            $this->sesionAprendizajes->removeElement($sesionAprendizaje);
            // set the owning side to null (unless already changed)
            if ($sesionAprendizaje->getCurso() === $this) {
                $sesionAprendizaje->setCurso(null);
            }
        }

        return $this;
    }
}
