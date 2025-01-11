<?php

namespace App\Controller;

use App\Entity\Evidencia;
use App\Entity\Denuncia;
use App\Form\EvidenciaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evidencia')]
class EvidenciaController extends AbstractController
{
    #[Route('/subir/{id}', name: 'evidencia_subir', methods: ['GET', 'POST'])]
    public function subir(
        Denuncia $denuncia,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($denuncia->getEstado() === 'resuelto') {
            $this->addFlash('error', 'No se pueden agregar evidencias a una denuncia resuelta.');
            return $this->redirectToRoute('denuncia_listar');
        }

        $evidencia = new Evidencia();
        $form = $this->createForm(EvidenciaType::class, $evidencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $archivo = $form->get('archivo')->getData();

            if ($archivo) {
                $nombreArchivo = uniqid() . '.' . $archivo->guessExtension();
                $uploadDir = $this->getParameter('uploads_directory');

                if (!in_array($archivo->getMimeType(), ['image/jpeg', 'image/png', 'video/mp4', 'audio/mpeg', 'application/pdf'])) {
                    $this->addFlash('error', 'Tipo de archivo no permitido.');
                    return $this->redirectToRoute('evidencia_subir', ['id' => $denuncia->getId()]);
                }

                if ($archivo->getSize() > 10 * 1024 * 1024) { // Limite de 10MB
                    $this->addFlash('error', 'El archivo excede el tamaño máximo permitido de 10MB.');
                    return $this->redirectToRoute('evidencia_subir', ['id' => $denuncia->getId()]);
                }

                try {
                    $archivo->move($uploadDir, $nombreArchivo);
                    $evidencia->setArchivo($nombreArchivo);
                    $evidencia->setDenuncia($denuncia);

                    $entityManager->persist($evidencia);
                    $entityManager->flush();

                    $this->addFlash('success', 'Evidencia subida correctamente.');
                    return $this->redirectToRoute('denuncia_listar');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Error al subir el archivo.');
                }
            }
        }

        return $this->render('evidencia/evidencia_subir.html.twig', [
            'form' => $form->createView(),
            'denuncia' => $denuncia,
        ]);
    }

    #[Route('/listar/{id}', name: 'evidencia_listar', methods: ['GET'])]
    public function listar(Denuncia $denuncia): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('evidencia/evidencia_listar.html.twig', [
            'denuncia' => $denuncia,
            'evidencias' => $denuncia->getEvidencias(),
        ]);
    }

    #[Route('/descargar/{id}', name: 'evidencia_descargar', methods: ['GET'])]
    public function descargar(Evidencia $evidencia): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $rutaArchivo = $this->getParameter('uploads_directory') . '/' . $evidencia->getArchivo();
        if (!file_exists($rutaArchivo)) {
            $this->addFlash('error', 'El archivo no existe.');
            return $this->redirectToRoute('denuncia_listar');
        }

        return $this->file($rutaArchivo);
    }

    #[Route('/eliminar/{id}', name: 'evidencia_eliminar', methods: ['POST'])]
    public function eliminar(
        Evidencia $evidencia,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $denuncia = $evidencia->getDenuncia();
        if ($denuncia->getEstado() === 'resuelto') {
            $this->addFlash('error', 'No se pueden eliminar evidencias de una denuncia resuelta.');
            return $this->redirectToRoute('denuncia_listar_todas');
        }

        $rutaArchivo = $this->getParameter('uploads_directory') . '/' . $evidencia->getArchivo();
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo);
        }

        $entityManager->remove($evidencia);
        $entityManager->flush();

        $this->addFlash('success', 'Evidencia eliminada correctamente.');
        return $this->redirectToRoute('denuncia_listar_todas');
    }
}
