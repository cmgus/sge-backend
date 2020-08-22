<?php

namespace App\Controller\Api;

use App\Entity\Docente;
use App\Entity\Persona;
use App\Entity\Rol;
use App\Repository\DocenteRepository;
use App\Repository\PersonaRepository;
use App\Repository\RolRepository;
use App\Repository\UsuarioRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DocenteController extends AbstractController
{
    /**
     * @Route("/docente", methods="GET")
     */
    public function list(Request $req, UsuarioRepository $usuRepo, DocenteRepository $docRepo)
    {
        $usuario = $usuRepo->findOneBy(['token' => $req->query->get('token')]);
        if (!$usuario) {
            return $this->json([
                'code' => 'INVALID_TOKEN',
                'data' => []
            ]);
        }
        $recursos = [];
        foreach ($usuario->getPersona()->getRol()->getRecursos() as $recurso) {
            $recursos[] = $recurso->getUri();
        }
        if (!in_array('docente', $recursos)) {
            return $this->json([
                'code' => 'UNAUTHORIZED',
                'data' => []
            ]);
        }
        $docentes = $docRepo->findAll();
        $docentesArray = [];
        foreach ($docentes as $docente) {
            $docentesArray[] = [
                'id' => $docente->getId(),
                'inicio' => $docente->getIncio()->format('Y-m-d'),
                'fin' => $docente->getFin()->format('Y-m-d'),
                'persona' => [
                    'id' => $docente->getPersona()->getId(),
                    'nombres' => $docente->getPersona()->getNombres(),
                    'apellidos' => $docente->getPersona()->getApellidos(),
                    'dni' => $docente->getPersona()->getDni(),
                    'direccion' => $docente->getPersona()->getDireccion()
                ]
            ];
        }
        return $this->json([
            'code' => 'LIST_DOCENTES',
            'data' => $docentesArray
        ]);
    }
    /**
     * @Route("/docente", methods="POST")
     */
    public function create(Request $req, UsuarioRepository $usuRepo, RolRepository $rolRepo, EntityManagerInterface $em)
    {
        $usuario = $usuRepo->findOneBy(['token' => $req->query->get('token')]);
        if (!$usuario) {
            return $this->json([
                'code' => 'INVALID_TOKEN',
                'data' => []
            ]);
        }
        $recursos = [];
        foreach ($usuario->getPersona()->getRol()->getRecursos() as $recurso) {
            $recursos[] = $recurso->getUri();
        }
        if (!in_array('docente', $recursos)) {
            return $this->json([
                'code' => 'UNAUTHORIZED',
                'data' => []
            ]);
        }
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
        $newDocente = new Docente;
        $newDocente->setIncio(new DateTime($content->inicio));
        $newDocente->setFin(new DateTime($content->fin));
        $newDocente->setPersona($newPersona);
        $em->persist($newPersona);
        $em->persist($newDocente);
        $em->flush();
        return $this->json([
            'id' => $newDocente->getId(),
            'fin' => $newDocente->getIncio()->format('Y-m-d'),
            'inicio' => $newDocente->getFin()->format('Y-m-d'),
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
     *@Route("/docente/{id}", methods="PUT")
     */
    public function update(Request $req, string $id, PersonaRepository $perRepo, DocenteRepository $docRepo, EntityManagerInterface $em)
    {
        $content = json_decode($req->getContent());
        $docente = $docRepo->findOneBy(['id' => $id]);
        if (!$docente) {
            return $this->json([
                'code' => 'DOCENTE_NOT_FOUND',
                'data' => []
            ]);
        }
        $docente->setIncio(new DateTime($content->inicio));
        $docente->setFin(new DateTime($content->fin));

        $persona = $perRepo->findOneBy(['id' => $content->persona->id]);
        $persona->setNombres($content->persona->nombres);
        $persona->setApellidos($content->persona->apellidos);
        $persona->setNombres($content->persona->nombres);
        $persona->setDireccion($content->persona->direccion);
        $persona->setDni($content->persona->dni);
        $docente->setPersona($persona);
        $em->persist($docente);
        $em->persist($persona);
        $em->flush();
        return $this->json([
            'id' => $docente->getId(),
            'fin' => $docente->getIncio()->format('Y-m-d'),
            'inicio' => $docente->getFin()->format('Y-m-d'),
            'persona' => [
                'id' => $persona->getId(),
                'nombres' => $persona->getNombres(),
                'apellidos' => $persona->getApellidos(),
                'dni' => $persona->getDni()
            ]
        ]);
    }
    /**
     * @Route("/docente/{id}", methods="DELETE")
     */
    public function destroy(Request $req, string $id, DocenteRepository $docRepo, EntityManagerInterface $em)
    {
        //$content = json_decode($req->getContent());
        $docente = $docRepo->findOneBy(['id' => $id]);
        $docente->setPersona(null);
        foreach ($docente->getEstudiantes() as $estudiante) {
            //$estudiante->setDocente(null);
            $docente->removeEstudiante($estudiante);
        }
        foreach ($docente->getSesionAprendizajes() as $session) {
            $docente->removeSesionAprendizaje($session);
        }
        $em->flush();
        $em->remove($docente);
        $em->flush();
        return $this->json([]);
    }
}
