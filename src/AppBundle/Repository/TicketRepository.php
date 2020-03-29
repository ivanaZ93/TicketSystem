<?php

namespace AppBundle\Repository;

use AppBundle\Entity\SearchParameters;
use Doctrine\ORM\EntityRepository;

class TicketRepository extends EntityRepository
{
    public function findByParameters(SearchParameters $searchParameters)
    {
        $dql = 'SELECT t FROM AppBundle:Ticket AS t';
        $where = [];
        $parameters = [];
        if (!empty($searchParameters->status)){
            $where[] = 't.status = :status';
            $parameters['status'] = $searchParameters->status;
        }
        if (!empty($searchParameters->priority)){
            $where[] = 't.priority = :priority';
            $parameters['priority'] = $searchParameters->priority;
        }
        if ([] !== $where) {
            $dql .= ' WHERE '.implode(' AND ', $where);
        }
        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters($parameters)
            ->getResult();
    }
}
