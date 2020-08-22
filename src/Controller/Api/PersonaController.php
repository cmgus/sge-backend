<?php

namespace App\Controller\Api;

use App\Entity\Persona;
use App\Repository\PersonaRepository;
use App\Repository\RolRepository;
use App\Repository\UsuarioRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PersonaController extends AbstractController
{
    /**
     * @Route("/persona", methods="GET")
     */
    public function list(Request $req, UsuarioRepository $usuRepo , PersonaRepository $personaRepository)
    {
        $usuario = $usuRepo->findOneBy(['token' => $req->query->get('token')]);
        $usuario->getPersona();
        if (!$usuario) {
            return $this->json([
                'code' => 'NO_TOKEN',
                'data' => []
            ]);
        }
        $uris = [];
        foreach ($usuario->getPersona()->getRol()->getRecursos() as $uri) {
            $uris[] = $uri;
        }
        if (!in_array('persona', $uri)) {
            return $this->json([
                'code' => 'UNAUTHORIZED',
                'data' => []
            ]);
        }
        $personas = $personaRepository->findAll();
        $personasArray = [];
        foreach ($personas as $persona) {
            $personasArray[] = [
                'id' => $persona->getId(),
                'nombres' => $persona->getNombres(),
                'dni' => $persona->getDni(),
                'rol' => [
                    'id' => $persona->getRol()->getId(),
                    'nombre' => $persona->getRol()->getNombre()
                ]
            ];
        }
        return $this->json($personasArray);
    }
    /**
     * @Route("/persona", methods="POST")
     */
    public function create(Request $request, RolRepository $rolRepo ,EntityManagerInterface $em)
    {
        $content = json_decode($request->getContent());
        $newPersona = new Persona;
        $newPersona->setDni($content->dni);
        $newPersona->setNombres($content->nombres);
        $newPersona->setApellidos($content->apellidos);
        $newPersona->setDireccion($content->direccion);
        $newPersona->setGenero($content->genero);

        $newPersona->setNacimiento(new DateTime($content->nacimiento));
        $rolId = $content->rol->id;
        $rol = $rolRepo->findOneBy(['id' => $rolId]);
        $newPersona->setRol($rol);
        $em->persist($newPersona);
        $em->flush($newPersona);
        return $this->json([
            'id' => $newPersona->getId(),
            'dni' => $newPersona->getDni(),
            'nombres' => $newPersona->getNombres(),
            'apellidos' => $newPersona->getApellidos(),
            'direccion' => $newPersona->getDireccion(),
            'genero' => $newPersona->getGenero(),
            'nacimiento' => $newPersona->getNacimiento()->format('Y-m-d'),
            'rol' => [
                'id' => $rol->getId(),
                'nombre' => $rol->getNombre()
            ]
        ]);
    }
}
