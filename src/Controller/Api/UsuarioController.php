<?php

namespace App\Controller\Api;

use App\Entity\Usuario;
use App\Repository\PersonaRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/usuario", methods="GET")
     */
    public function list(Request $req, UsuarioRepository $usuRepo)
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
        $usuarios = $usuRepo->findAll();
        $usuariosArray = [];
        foreach ($usuarios as $usuario) {
            $usuariosArray[] = [
                'id' => $usuario->getId(),
                'email' => $usuario->getEmail(),
                'persona' => [
                    'nombres' => $usuario->getPersona()->getNombres(),
		    'dni' => $usuario->getPersona()->getDni(),
                    'apellidos' => $usuario->getPersona()->getApellidos(),
                    'rol' => [
                        'id' => $usuario->getPersona()->getRol()->getId(),
                        'nombre' => $usuario->getPersona()->getRol()->getNombre()
                    ]
                ],
            ];
        }
	return $this->json([
	    'code' => 'LIST_USUARIOS',
	    'data' => $usuariosArray
	]);
    }
    /**
     * @Route("/usuario", methods="POST")
     */
    public function create(Request $request, PersonaRepository $perRepo, EntityManagerInterface $em)
    {

        $content = json_decode($request->getContent());
        $newUsuario = new Usuario;
        $newUsuario->setEmail($content->email);
        $newUsuario->setPassword($content->password);
        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);
        $newUsuario->setToken($token);
        $persona = $perRepo->findOneBy(['dni' => $content->persona->dni]);
        $newUsuario->setPersona($persona);
        /* $telefonosArray = []; */
        /* foreach ($persona->getTelefonos() as $telefono) {
            $telefonosArray[] = [
                'id' => $telefono->getId(),
                'numero' => $telefono->getNumero()
            ];
        } */
        $em->persist($newUsuario);
        $em->flush();
        return $this->json([
            'id' => $newUsuario->getId(),
            'email' => $newUsuario->getEmail(),
            'persona' => [
                'nombres' => $persona->getNombres(),
                'apellidos' => $persona->getApellidos(),
                'dni' => $persona->getDni(),
		'rol' => [
			'id' => $persona->getRol()->getId(),
			'nombre' => $persona->getRol()->getNombre()
		]
            ],
        ]);
    }
    /**
     *@Route("/usuario/{id}", methods="PUT")
     */
    public function update(Request $req, string $id, PersonaRepository $perRepo, UsuarioRepository $usuRepo, EntityManagerInterface $em) {
        $content = json_decode($req->getContent());
        $usuario = $usuRepo->findOneBy(['id' => $id]);
        if (!$usuario) {
            return $this->json([
                'code' => 'USER_NOT_FOUND',
                'data' => []
            ]);
	}
	$persona = $perRepo->findOneBy(['dni' => $content->persona->dni]);
	$persona->setNombres($content->persona->nombres);
	$persona->setApellidos($content->persona->apellidos);
        if ($content->email) {
            $usuario->setEmail($content->email);
	}
	$usuario->setPersona($persona);
	$em->persist($persona);
        $em->persist($usuario);
        $em->flush();
        return $this->json([
            'code' => 'USER_UPDATED',
            'data' => [
                'email' => $usuario->getEmail()
            ]
        ]);
    }
}
