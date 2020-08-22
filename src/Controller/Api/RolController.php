<?php

namespace App\Controller\Api;

use App\Entity\Rol;
use App\Repository\RecursoRepository;
use App\Repository\RolRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class RolController extends AbstractController
{
    /**
     * @Route("/rol", methods="GET")
     */
    public function list(Request $req, UsuarioRepository $usuRepo, RolRepository $rolRepository)
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
        $roles = $rolRepository->findAll();
        $rolesArray = [];
        foreach ($roles as $rol) {
            $recursosArray = [];
            foreach ($rol->getRecursos() as $recurso) {
                $recursosArray[] = [
                    'id' => $recurso->getId(),
                    'uri' => $recurso->getUri()
                ];
            }
            $rolesArray[] = [
                'id' => $rol->getId(),
                'nombre' => $rol->getNombre(),
                'recursos' => $recursosArray
            ];
            //$rolesArray[] = ['recursos' => $recursosArray];
        }
        return $this->json($rolesArray);
    }
    /**
     * @Route("/rol", methods="POST")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $content = json_decode($request->getContent());
        $newRol = new Rol;
        $newRol->setNombre($content->nombre);
        $em->persist($newRol);
        $em->flush();
        return $this->json([
            'nombre' => $newRol->getNombre(),
            'id' => $newRol->getId()
        ], 201);
    }
    /**
     * @Route("/rol/{id}", methods="PUT")
     */
    public function update(int $id, Request $req, RolRepository $rolRepo, RecursoRepository $recRepo, EntityManagerInterface $em)
    {
        $rol = $rolRepo->findOneBy(['id' => $id]);
        if ($rol === null) {
            return $this->json([], 404);
        }
        $content = json_decode($req->getContent());

        if (isset($content->nombre)) {
            $rol->setNombre($content->nombre);
        }

        $recursosId = $content->recursos;
        $recursosArray = [];
        foreach ($rol->getRecursos() as $recurso) {
            $rol->removeRecurso($recurso);
        }
        foreach ($recursosId as $recursoId) {
            $recurso = $recRepo->findOneBy(['id' => $recursoId]);
            if ($recRepo) {
                $rol->addRecurso($recurso);
                $recursosArray[] = [
                    'id' => $recurso->getId(),
                    'uri' => $recurso->getUri(),
                ];
            }
        }
        $em->persist($rol);
        $em->flush();
        return $this->json([
            'id' => $rol->getId(),
            'nombre' => $rol->getNombre(),
            'recursos' => $recursosArray
        ]);
    }
}
