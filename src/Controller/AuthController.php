<?php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/login")
     */
    public function login(Request $req, UsuarioRepository $usuRepo)
    {
        $content = json_decode($req->getContent());
        $usuario = $usuRepo->findOneBy(['email' => $content->email]);
        if (!$usuario) {
            return $this->json([
                'code' => 'USER_NOT_FOUND',
                'data' => []
            ]);
        }
        if ($usuario->getPassword() !== $content->password) {
            return $this->json([
                'code' => 'INVALID_CREDENTIALS',
                'data' => []
            ]);
        }
        $recursos = $usuario->getPersona()->getRol()->getRecursos();
        $recursosArray = [];
        foreach ($recursos as $recurso) {
            $recursosArray[] = [
                'id' => $recurso->getId(),
                'uri' => $recurso->getUri()
            ];
        }
        return $this->json([
            'code' => 'SESSION_STARTED',
            'data' => [
                'usuario' => [
                    'id' => $usuario->getId(),
                    'token' => $usuario->getToken()
                ],
                'recursos' => $recursosArray
            ]
        ]);
    }
    /**
     * @Route("/logout")
     */
    public function logout()
    {
        return $this->json([
            'code' => 'SESSION_FINISHED',
            'data' => []
        ]);
    }
}
