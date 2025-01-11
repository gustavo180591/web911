<?php

namespace App\Controller;

use App\Entity\ReporteEstadistico;
use App\Form\ReporteEstadisticoType;
use App\Repository\DenunciaRepository;
use App\Repository\ReporteEstadisticoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/reporte')]
class ReporteEstadisticoController extends AbstractController
{
    #[Route('/crear', name: 'reporte_estadistico_crear', methods: ['GET', 'POST'])]
    public function crear(
        Request $request,
        EntityManagerInterface $entityManager,
        DenunciaRepository $denunciaRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $reporte = new ReporteEstadistico();
        $form = $this->createForm(ReporteEstadisticoType::class, $reporte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $denuncias = $denunciaRepository->findByDateRange(
                $reporte->getFechaInicio(),
                $reporte->getFechaFin()
            );
            $reporte->setDatos($this->generateMetrics($denuncias));
            $entityManager->persist($reporte);
            $entityManager->flush();

            $this->addFlash('success', 'Reporte creado correctamente.');
            return $this->redirectToRoute('reporte_estadistico_listar');
        }

        return $this->render('reporte/reporte_estadistico_crear.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/listar', name: 'reporte_estadistico_listar', methods: ['GET'])]
    public function listar(ReporteEstadisticoRepository $reporteRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('reporte/reporte_estadistico_listar.html.twig', [
            'reportes' => $reporteRepository->findAll(),
        ]);
    }

    #[Route('/detalle/{id}', name: 'reporte_estadistico_detalle', methods: ['GET'])]
    public function detalle(ReporteEstadistico $reporte): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('reporte/reporte_estadistico_detalle.html.twig', [
            'reporte' => $reporte,
            'datos' => $reporte->getDatos(),
        ]);
    }

    #[Route('/exportar/{id}/{formato}', name: 'reporte_estadistico_exportar', methods: ['GET'])]
    public function exportar(
        ReporteEstadistico $reporte,
        string $formato,
        SerializerInterface $serializer
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $datos = $reporte->getDatos();
        $nombreArchivo = sprintf('reporte_%s.%s', $reporte->getId(), $formato);

        switch ($formato) {
            case 'json':
                $contenido = $serializer->serialize($datos, 'json');
                $tipoContenido = 'application/json';
                break;
            case 'csv':
                $contenido = $this->generateCsv($datos);
                $tipoContenido = 'text/csv';
                break;
            case 'pdf':
                $contenido = $this->generatePdf($datos);
                $tipoContenido = 'application/pdf';
                break;
            default:
                $this->addFlash('error', 'Formato no soportado.');
                return $this->redirectToRoute('reporte_estadistico_listar');
        }

        return new Response($contenido, 200, [
            'Content-Type' => $tipoContenido,
            'Content-Disposition' => "attachment; filename=$nombreArchivo",
        ]);
    }

    #[Route('/filtrar', name: 'reporte_estadistico_filtrar', methods: ['GET', 'POST'])]
    public function filtrar(
        Request $request,
        DenunciaRepository $denunciaRepository
    ): Response {
        $filtros = $request->query->all();
        $denunciasFiltradas = $denunciaRepository->findByFilters($filtros);

        return $this->render('reporte/reporte_estadistico_filtrar.html.twig', [
            'denuncias' => $denunciasFiltradas,
            'filtros' => $filtros,
        ]);
    }

    private function generateMetrics(array $denuncias): array
    {
        $metrics = [
            'total' => count($denuncias),
            'categorias' => [],
            'ubicaciones' => [],
        ];

        foreach ($denuncias as $denuncia) {
            $categoria = $denuncia->getCategoria();
            $ubicacion = $denuncia->getUbicacion();

            if (!isset($metrics['categorias'][$categoria])) {
                $metrics['categorias'][$categoria] = 0;
            }
            $metrics['categorias'][$categoria]++;

            if (!isset($metrics['ubicaciones'][$ubicacion])) {
                $metrics['ubicaciones'][$ubicacion] = 0;
            }
            $metrics['ubicaciones'][$ubicacion]++;
        }

        return $metrics;
    }

    private function generateCsv(array $datos): string
    {
        $csv = "Métrica,Valor\n";

        foreach ($datos as $clave => $valor) {
            if (is_array($valor)) {
                foreach ($valor as $subClave => $subValor) {
                    $csv .= "$clave ($subClave),$subValor\n";
                }
            } else {
                $csv .= "$clave,$valor\n";
            }
        }

        return $csv;
    }

    private function generatePdf(array $datos): string
    {
        $contenido = "Reporte Estadístico\n\n";
        foreach ($datos as $clave => $valor) {
            $contenido .= strtoupper($clave) . ":\n";
            if (is_array($valor)) {
                foreach ($valor as $subClave => $subValor) {
                    $contenido .= "- $subClave: $subValor\n";
                }
            } else {
                $contenido .= "- $valor\n";
            }
            $contenido .= "\n";
        }
        return $contenido;
    }
}
