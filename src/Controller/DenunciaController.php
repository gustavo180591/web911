<?php

namespace App\Controller;

use App\Entity\Denuncia;
use App\Form\DenunciaType;
use App\Repository\DenunciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/denuncia')]
class DenunciaController extends AbstractController
{
    #[Route('/crear', name: 'denuncia_crear', methods: ['GET', 'POST'])]
    public function crear(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $denuncia = new Denuncia();
        $form = $this->createForm(DenunciaType::class, $denuncia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $this->getUser();
            if ($usuario && !$form->get('anonima')->getData()) {
                $denuncia->setUsuario($usuario);
            } else {
                $denuncia->setUsuario(null); // Denuncia anónima
            }

            $denuncia->setNumeroCaso(uniqid('CASO-')); // Generar número de caso único
            $entityManager->persist($denuncia);
            $entityManager->flush();

            // Enviar confirmación de recepción
            if ($usuario) {
                $this->addFlash(
                    'success',
                    'Denuncia creada correctamente. Tu número de caso es: ' . $denuncia->getNumeroCaso()
                );
            } else {
                $this->addFlash('success', 'Denuncia anónima creada correctamente.');
            }

            return $this->redirectToRoute('denuncia_listar');
        }

        return $this->render('denuncia/denuncia_crear.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/listar', name: 'denuncia_listar', methods: ['GET'])]
    public function listar(
        DenunciaRepository $denunciaRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $usuario = $this->getUser();
        if (!$usuario) {
            throw $this->createAccessDeniedException('Acceso denegado.');
        }

        $query = $denunciaRepository->findByUsuarioQuery($usuario);
        $paginacion = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('denuncia/denuncia_listar.html.twig', [
            'denuncias' => $paginacion,
        ]);
    }

    #[Route('/listar-todas', name: 'denuncia_listar_todas', methods: ['GET'])]
    public function listarTodas(
        DenunciaRepository $denunciaRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $query = $denunciaRepository->findAllQuery();
        $paginacion = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('denuncia/denuncia_listar_admin.html.twig', [
            'denuncias' => $paginacion,
        ]);
    }

    #[Route('/actualizar/{id}', name: 'denuncia_actualizar', methods: ['GET', 'POST'])]
    public function actualizar(
        Denuncia $denuncia,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(DenunciaType::class, $denuncia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Denuncia actualizada correctamente.');

            return $this->redirectToRoute('denuncia_listar_todas');
        }

        return $this->render('denuncia/denuncia_editar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cambiar-estado/{id}', name: 'denuncia_cambiar_estado', methods: ['POST'])]
    public function cambiarEstado(
        Denuncia $denuncia,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $estado = $request->request->get('estado');
        if (!$estado) {
            $this->addFlash('error', 'Debe seleccionar un estado.');
            return $this->redirectToRoute('denuncia_listar_todas');
        }

        $denuncia->setEstado($estado);
        $entityManager->flush();

        $this->addFlash('success', 'Estado de la denuncia actualizado correctamente.');
        return $this->redirectToRoute('denuncia_listar_todas');
    }

    #[Route('/exportar', name: 'denuncia_exportar', methods: ['GET'])]
    public function exportar(DenunciaRepository $denunciaRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $denuncias = $denunciaRepository->findAll();
        $csvContent = "ID,Categoría,Descripción,Estado,Prioridad,Numero de Caso\n";

        foreach ($denuncias as $denuncia) {
            $csvContent .= sprintf(
                "%d,%s,%s,%s,%s,%s\n",
                $denuncia->getId(),
                $denuncia->getCategoria(),
                $denuncia->getDescripcion(),
                $denuncia->getEstado(),
                $denuncia->getPrioridad(),
                $denuncia->getNumeroCaso()
            );
        }

        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="denuncias.csv"');

        return $response;
    }

    #[Route('/geolocalizacion', name: 'denuncia_geolocalizacion', methods: ['GET'])]
    public function geolocalizacion(): Response
    {
        return $this->render('denuncia/denuncia_mapa.html.twig');
    }
}
