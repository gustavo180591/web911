<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/usuario')]
class UsuarioController extends AbstractController
{
    #[Route('/registro', name: 'usuario_registro', methods: ['GET', 'POST'])]
    public function registro(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ): Response {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario->setPassword($passwordEncoder->encodePassword($usuario, $usuario->getPassword()));
            $usuario->setRol('usuario');
            $usuario->setVerificado(false);
            $entityManager->persist($usuario);
            $entityManager->flush();

            // Enviar email de verificación (implementación futura)
            $this->addFlash('success', 'Usuario registrado correctamente. Revisa tu correo para verificar tu cuenta.');

            return $this->redirectToRoute('usuario_login');
        }

        return $this->render('usuario/registro.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'usuario_login', methods: ['GET', 'POST'])]
    public function login(): Response
    {
        // Symfony gestiona automáticamente el login con su sistema de seguridad
        return $this->render('usuario/login.html.twig');
    }

    #[Route('/logout', name: 'usuario_logout', methods: ['GET'])]
    public function logout(): void
    {
        // Symfony gestiona automáticamente el logout con su sistema de seguridad
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/perfil', name: 'usuario_perfil', methods: ['GET', 'POST'])]
    public function perfil(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $usuario = $this->getUser();

        if (!$usuario) {
            throw $this->createAccessDeniedException('Acceso denegado.');
        }

        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Perfil actualizado correctamente.');

            return $this->redirectToRoute('usuario_perfil');
        }

        return $this->render('usuario/perfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/listado', name: 'usuario_listado', methods: ['GET'])]
    public function listado(UsuarioRepository $usuarioRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('usuario/listado.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
        ]);
    }

    #[Route('/activar/{id}', name: 'usuario_activar', methods: ['POST'])]
    public function activar(
        Usuario $usuario,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $usuario->setVerificado(true);
        $entityManager->flush();

        $this->addFlash('success', 'Usuario activado correctamente.');

        return $this->redirectToRoute('usuario_listado');
    }

    #[Route('/desactivar/{id}', name: 'usuario_desactivar', methods: ['POST'])]
    public function desactivar(
        Usuario $usuario,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $usuario->setVerificado(false);
        $entityManager->flush();

        $this->addFlash('success', 'Usuario desactivado correctamente.');

        return $this->redirectToRoute('usuario_listado');
    }

    #[Route('/eliminar/{id}', name: 'usuario_eliminar', methods: ['POST'])]
    public function eliminar(
        Usuario $usuario,
        UsuarioRepository $usuarioRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Asociar denuncias del usuario eliminado al "usuario genérico"
        $usuarioGenerico = $usuarioRepository->findOneBy(['email' => 'anonimo@domain.com']);
        foreach ($usuario->getDenuncias() as $denuncia) {
            $denuncia->setUsuario($usuarioGenerico);
        }

        $entityManager->remove($usuario);
        $entityManager->flush();

        $this->addFlash('success', 'Usuario eliminado correctamente.');

        return $this->redirectToRoute('usuario_listado');
    }
}
