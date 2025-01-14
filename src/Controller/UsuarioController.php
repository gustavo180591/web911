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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/usuario')]
class UsuarioController extends AbstractController
{
    #[Route('/registro', name: 'usuario_registro', methods: ['GET', 'POST'])]
public function registro(Request $request, EntityManagerInterface $entityManager): Response
{
    $usuario = new Usuario();
    $form = $this->createForm(UsuarioType::class, $usuario);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $usuario->setPassword(password_hash($usuario->getPassword(), PASSWORD_BCRYPT));
        $usuario->setRol('ROLE_USER');
        $usuario->setVerificado(false);
        $entityManager->persist($usuario);
        $entityManager->flush();

        // Agrega una redirección explícita
        return $this->redirectToRoute('usuario_login');
    }

    return $this->render('usuario/usuario_registro.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/listar', name: 'usuario_listar', methods: ['GET'])]
    public function listar(UsuarioRepository $usuarioRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $usuarios = $usuarioRepository->findAll();

        return $this->render('usuario/usuario_listar.html.twig', [
            'usuarios' => $usuarios,
        ]);
    }

    #[Route('/login', name: 'usuario_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('usuario/usuario_login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'usuario_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \LogicException('Este método es interceptado por el sistema de seguridad.');
    }

    #[Route('/perfil', name: 'usuario_perfil', methods: ['GET', 'POST'])]
    public function perfil(Request $request, EntityManagerInterface $entityManager): Response
    {
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

        return $this->render('usuario/usuario_perfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/activar/{id}', name: 'usuario_activar', methods: ['POST'])]
    public function activar(Usuario $usuario, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $usuario->setVerificado(true);
        $entityManager->flush();

        $this->addFlash('success', 'Usuario activado correctamente.');

        return $this->redirectToRoute('usuario_listar');
    }

    #[Route('/desactivar/{id}', name: 'usuario_desactivar', methods: ['POST'])]
    public function desactivar(Usuario $usuario, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $usuario->setVerificado(false);
        $entityManager->flush();

        $this->addFlash('success', 'Usuario desactivado correctamente.');

        return $this->redirectToRoute('usuario_listar');
    }

    #[Route('/eliminar/{id}', name: 'usuario_eliminar', methods: ['POST'])]
    public function eliminar(
        Usuario $usuario,
        UsuarioRepository $usuarioRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $usuarioGenerico = $usuarioRepository->findOneBy(['email' => 'anonimo@domain.com']);

        foreach ($usuario->getDenuncias() as $denuncia) {
            $denuncia->setUsuario($usuarioGenerico);
        }

        $entityManager->remove($usuario);
        $entityManager->flush();

        $this->addFlash('success', 'Usuario eliminado correctamente.');

        return $this->redirectToRoute('usuario_listar');
    }

    #[Route('/verificar-email/{token}', name: 'usuario_verificar_email', methods: ['GET'])]
    public function verificarEmail(string $token, UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManager): Response
    {
        $usuario = $usuarioRepository->findOneBy(['verificationToken' => $token]);

        if (!$usuario) {
            $this->addFlash('error', 'Token de verificación inválido o expirado.');
            return $this->redirectToRoute('usuario_login');
        }

        $usuario->setVerificado(true);
        $usuario->setVerificationToken(null);
        $entityManager->flush();

        $this->addFlash('success', 'Cuenta verificada correctamente.');
        return $this->redirectToRoute('usuario_login');
    }
}
