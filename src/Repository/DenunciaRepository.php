<?php

namespace App\Repository;

use App\Entity\Denuncia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Denuncia>
 */
class DenunciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Denuncia::class);
    }

    /**
     * Obtiene el conteo de denuncias agrupadas por estado.
     *
     * @return array Devuelve un array con el estado y el conteo correspondiente.
     */
    public function getCountByEstado(): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.estado, COUNT(d.id) as total')
            ->groupBy('d.estado')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtiene el conteo de denuncias agrupadas por categoría.
     *
     * @return array Devuelve un array con la categoría y el conteo correspondiente.
     */
    public function getCountByCategoria(): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.categoria, COUNT(d.id) as total')
            ->groupBy('d.categoria')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtiene las denuncias urgentes con prioridad "alta".
     *
     * @return array Devuelve un array de denuncias ordenadas por fecha (descendente).
     */
    public function findUrgentes(): array
    {
        return $this->findBy(['prioridad' => 'alta'], ['fechaHora' => 'DESC'], 5);
    }

    /**
     * Encuentra denuncias que no tienen autoridades asignadas.
     *
     * @return array Devuelve un array de denuncias sin autoridades.
     */
    public function findWithoutAuthority(): array
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.autoridades', 'a')
            ->where('a.id IS NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * Encuentra denuncias basadas en una categoría específica.
     *
     * @param string $categoria La categoría de las denuncias a buscar.
     * @return array Devuelve un array de denuncias de la categoría indicada.
     */
    public function findByCategoria(string $categoria): array
    {
        return $this->findBy(['categoria' => $categoria], ['fechaHora' => 'DESC']);
    }
}
