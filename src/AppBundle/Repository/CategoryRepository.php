<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCategory(){
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->select(['category'])
            ->from(Category::class, 'category');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
