<?php

namespace App\Repository;

use App\Entity\ReporteEstadistico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReporteEstadistico>
 */
class ReporteEstadisticoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReporteEstadistico::class);
    }

    /**
     * Encuentra los reportes estadísticos más recientes.
     *
     * @return array Devuelve un array con los 5 reportes más recientes ordenados por fecha_fin.
     */
    public function findRecent(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.fecha_fin', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * Encuentra reportes filtrados por tipo.
     *
     * @param string $tipo Tipo de reporte (mensual, semanal, personalizado).
     * @return array Devuelve un array de reportes del tipo especificado.
     */
    public function findByTipo(string $tipo): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.tipo = :tipo')
            ->setParameter('tipo', $tipo)
            ->orderBy('r.fecha_fin', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Encuentra reportes filtrados por rango de fechas.
     *
     * @param \DateTimeInterface $inicio Fecha de inicio.
     * @param \DateTimeInterface $fin Fecha de fin.
     * @return array Devuelve un array de reportes que coinciden con el rango de fechas.
     */
    public function findByDateRange(\DateTimeInterface $inicio, \DateTimeInterface $fin): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.fecha_inicio >= :inicio')
            ->andWhere('r.fecha_fin <= :fin')
            ->setParameter('inicio', $inicio)
            ->setParameter('fin', $fin)
            ->orderBy('r.fecha_fin', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
