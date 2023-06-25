<?php

namespace App\Utils;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as OrmPaginator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Paginator
{
    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $lastPage;

    protected $items;

    private int $perPage;

    public function __construct(
        protected ParameterBagInterface $params
    ) {
        $this->perPage = (int) $this->params->get('perPage');
    }

    /**
     * @param QueryBuilder|Query $query
     */
    public function paginate($query, int $page = 1): Paginator
    {
        $paginator = new OrmPaginator($query, true);

        $paginator
            ->getQuery()
            ->setFirstResult($this->perPage * ($page - 1))
            ->setMaxResults($this->perPage);
        $paginator->setUseOutputWalkers(false);

        $this->total = $paginator->count();
        $this->lastPage = (int) ceil($paginator->count() / $paginator->getQuery()->getMaxResults());
        $this->items = $paginator;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    public function getItems()
    {
        return $this->items;
    }
}
