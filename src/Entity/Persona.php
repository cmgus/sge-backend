<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonaRepository::class)
 */
class Persona
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private $dni;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $nombres;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $genero;

    /**
     * @ORM\Column(type="date")
     */
    private $nacimiento;

    /**
     * @ORM\OneToOne(targetEntity=Usuario::class, mappedBy="persona", cascade={"persist", "remove"})
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Rol::class, inversedBy="personas")
     */
    private $rol;

    /**
     * @ORM\OneToMany(targetEntity=Telefono::class, mappedBy="persona")
     */
    private $telefonos;

    /**
     * @ORM\OneToMany(targetEntity=Docente::class, mappedBy="persona")
     */
    private $docentes;

    /**
     * @ORM\OneToMany(targetEntity=Estudiante::class, mappedBy="persona")
     */
    private $estudiantes;

    /**
     * @ORM\OneToMany(targetEntity=Apoderado::class, mappedBy="persona")
     */
    private $apoderados;

    public function __construct()
    {
        $this->telefonos = new ArrayCollection();
        $this->docentes = new ArrayCollection();
        $this->estudiantes = new ArrayCollection();
        $this->apoderados = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getNacimiento(): ?\DateTimeInterface
    {
        return $this->nacimiento;
    }

    public function setNacimiento(\DateTimeInterface $nacimiento): self
    {
        $this->nacimiento = $nacimiento;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        // set (or unset) the owning side of the relation if necessary
        $newPersona = null === $usuario ? null : $this;
        if ($usuario->getPersona() !== $newPersona) {
            $usuario->setPersona($newPersona);
        }

        return $this;
    }

    public function getRol(): ?Rol
    {
        return $this->rol;
    }

    public function setRol(?Rol $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * @return Collection|Telefono[]
     */
    public function getTelefonos(): Collection
    {
        return $this->telefonos;
    }

    public function addTelefono(Telefono $telefono): self
    {
        if (!$this->telefonos->contains($telefono)) {
            $this->telefonos[] = $telefono;
            $telefono->setPersona($this);
        }

        return $this;
    }

    public function removeTelefono(Telefono $telefono): self
    {
        if ($this->telefonos->contains($telefono)) {
            $this->telefonos->removeElement($telefono);
            // set the owning side to null (unless already changed)
            if ($telefono->getPersona() === $this) {
                $telefono->setPersona(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Docente[]
     */
    public function getDocentes(): Collection
    {
        return $this->docentes;
    }

    public function addDocente(Docente $docente): self
    {
        if (!$this->docentes->contains($docente)) {
            $this->docentes[] = $docente;
            $docente->setPersona($this);
        }

        return $this;
    }

    public function removeDocente(Docente $docente): self
    {
        if ($this->docentes->contains($docente)) {
            $this->docentes->removeElement($docente);
            // set the owning side to null (unless already changed)
            if ($docente->getPersona() === $this) {
                $docente->setPersona(null);
            }
        }

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
            $estudiante->setPersona($this);
        }

        return $this;
    }

    public function removeEstudiante(Estudiante $estudiante): self
    {
        if ($this->estudiantes->contains($estudiante)) {
            $this->estudiantes->removeElement($estudiante);
            // set the owning side to null (unless already changed)
            if ($estudiante->getPersona() === $this) {
                $estudiante->setPersona(null);
            }
        }

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
            $apoderado->setPersona($this);
        }

        return $this;
    }

    public function removeApoderado(Apoderado $apoderado): self
    {
        if ($this->apoderados->contains($apoderado)) {
            $this->apoderados->removeElement($apoderado);
            // set the owning side to null (unless already changed)
            if ($apoderado->getPersona() === $this) {
                $apoderado->setPersona(null);
            }
        }

        return $this;
    }
}
