<?php

namespace App\Controller\Api;

use App\Entity\Docente;
use App\Entity\Estudiante;
use App\Entity\Persona;
use App\Repository\DocenteRepository;
use App\Repository\EstudianteRepository;
use App\Repository\PersonaRepository;
use App\Repository\RolRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EstudianteController extends AbstractController
{
    /**
     * @Route("/estudiante", methods="GET")
     */
    public function list(EstudianteRepository $estRepo)
    {
        $estudiantes = $estRepo->findAll();
        $estudianteArray = [];
        foreach ($estudiantes as $estudiante) {
            $estudianteArray[] = [
                'id' => $estudiante->getId(),
                'codigo' => $estudiante->getCodigo(),
                'persona' => [
                    'id' => $estudiante->getPersona()->getId(),
                    'nombres' => $estudiante->getPersona()->getNombres(),
                    'apellidos' => $estudiante->getPersona()->getApellidos(),
                    'dni' => $estudiante->getPersona()->getDni(),
                    'direccion' => $estudiante->getPersona()->getDireccion()
                ]
            ];
        }
        return $this->json([
            'code' => 'LIST_ESTUDIANTES',
            'data' => $estudianteArray
        ]);
    }
    /**
     * @Route("/estudiate/docente/{docente_dni}", methods="GET")
     */
    public function list_from_docente(string $docente_dni, DocenteRepository $docRepo)
    {
        $doc = $docRepo->findOneBy(['dni' => $docente_dni]);
        $estudiantesArray = [];
        foreach ($doc->getEstudiantes() as $estudiante) {
            $estudiantesArray[] = [
                'id' => $estudiante->getId(),
                'codigo' => $estudiante->getCodigo(),
                'persona' => [
                    'id' => $estudiante->getPersona()->getId(),
                    'nombres' => $estudiante->getPersona()->getNombres(),
                    'apellidos' => $estudiante->getPersona()->getApellidos(),
                    'direccion' => $estudiante->getPersona()->getDireccion()
                ]
            ];
        }
        return $this->json($estudiantesArray);
    }
    /**
     * @Route("/estudiante", methods="POST")
     */
    public function create(Request $req, DocenteRepository $docRepo ,RolRepository $rolRepo, EntityManagerInterface $em)
    {
        $content = json_decode($req->getContent());
        $newPersona = new Persona;
        $newPersona->setDni($content->persona->dni);
        $newPersona->setNombres($content->persona->nombres);
        $newPersona->setApellidos($content->persona->apellidos);
        $newPersona->setDireccion($content->persona->direccion);
        $newPersona->setGenero($content->persona->genero);
        $rol = $rolRepo->findOneBy(['nombre' => 'docente']);
        $newPersona->setRol($rol);
        $newPersona->setNacimiento(new DateTime($content->persona->nacimiento));

        $docente = $docRepo->findOneBy(['id' => $content->docente]);
        $new = new Estudiante;
        $new->setCodigo($content->codigo);
        $new->setDocente($docente);
        $new->setPersona($newPersona);
        $em->persist($newPersona);
        $em->persist($new);
        $em->flush();
        return $this->json([
            'id' => $new->getId(),
            'codigo' => $new->getCodigo(),
            'persona' => [
                'id' => $newPersona->getId(),
                'nombres' => $newPersona->getNombres(),
                'apellidos' => $newPersona->getApellidos(),
                'dni' => $newPersona->getDni(),
                'direccion' => $newPersona->getDireccion()
            ]
        ]);
    }
    /**
     * @Route("/estudiante/{id}", methods="PUT")
     */
    public function update(Request $req, string $id, PersonaRepository $perRepo, EstudianteRepository $estRepo, EntityManagerInterface $em)
    {
        $content = json_decode($req->getContent());
        $estudiante = $estRepo->findOneBy(['id' => $id]);
        if (!$estudiante) {
            return $this->json([
                'code' => 'DOCENTE_NOT_FOUND',
                'data' => []
            ]);
        }
        $estudiante->setCodigo($content->codigo);
        //$docente = $docRepo->findOneBy(['id' => $content->docente]);
        //$estudiante->setDocente($docente);

        $persona = $perRepo->findOneBy(['id' => $content->persona->id]);
        $persona->setNombres($content->persona->nombres);
        $persona->setApellidos($content->persona->apellidos);
        $persona->setNombres($content->persona->nombres);
        $persona->setDireccion($content->persona->direccion);
        $persona->setDni($content->persona->dni);
        $estudiante->setPersona($persona);
        $em->persist($persona);
        $em->persist($estudiante);
        $em->flush();
        return $this->json([
            'id' => $estudiante->getId(),
            'codigo' => $estudiante->getCodigo(),
            'persona' => [
                'id' => $persona->getId(),
                'nombres' => $persona->getNombres(),
                'apellidos' => $persona->getApellidos(),
                'dni' => $persona->getDni()
            ]
        ]);
    }
}
