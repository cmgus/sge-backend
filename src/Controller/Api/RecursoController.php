<?php

namespace App\Controller\Api;

use App\Entity\Recurso;
use App\Repository\RecursoRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class RecursoController extends AbstractController
{
    /**
     * @Route("/recurso", methods="GET")
     */
    public function list(Request $req, UsuarioRepository $usuRepo, RecursoRepository $recursoRepository)
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
        $recursos = $recursoRepository->findAll();
        $data = [];
        foreach ($recursos as $recurso) {
            $data[] = [
                'id' => $recurso->getId(),
                'uri' => $recurso->getUri()
            ];
        }
        return $this->json($data);
    }
    /**
     * @Route("/recurso", methods="POST")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $content = json_decode($request->getContent());
        $newRecurso = new Recurso;
        $newRecurso->setUri($content->uri);
        $entityManager->persist($newRecurso);
        $entityManager->flush();
        return $this->json([
            'id' => $newRecurso->getId(),
            'uri' => $newRecurso->getUri()
        ], 201);
    }
}
