<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\ModuleFonctionnaliteType as Module;
//use RS\UserBundle\Entity\User;

/**
 * EntreeRepository.
 */
class EntreeRepository extends EntityRepository
{
    public function myFindAll()
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->leftJoin('e.module', 'm')
            ->addSelect('m')
            ->leftJoin('e.commentaires', 'c')
            ->addSelect('c')
            ->leftJoin('e.images', 'i')
            ->addSelect('i')
            ->orderBy('e.id');

        return $queryBuilder->getQuery()->getResult();
    }

//    public function entreesParUserEtParModule(User $user, Module $module)
//    {
//        $queryBuilder = $this->createQueryBuilder('e')
//            ->leftJoin('e.commentaires', 'c')
//            ->addSelect('c')
//            ->leftJoin('e.images', 'i')
//            ->addSelect('i')
//            ->leftJoin('e.module', 'module')
//            ->leftJoin('module.parent', 'parent')
//            ->leftJoin('parent.parent', 'grandparent')
//            ->where('e.statut = :statut AND (e.module = :module OR module.parent = :module2 OR parent.parent = :module3) AND (e.createur = :createur OR e.assigne = :assigne)')
//            ->setParameter('statut', 'OUVERT')
//            ->setParameter('module', $module)
//            ->setParameter('module2', $module)
//            ->setParameter('module3', $module)
//            ->setParameter('createur', $user)
//            ->setParameter('assigne', $user)
//            ->orderBy('e.id');
//
//        return $queryBuilder->getQuery()->getResult();
//    }

    public function entreesOuvertesParModule(Module $module)
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->leftJoin('e.commentaires', 'c')
            ->addSelect('c')
            ->leftJoin('e.images', 'i')
            ->addSelect('i')
            ->leftJoin('e.module', 'module')
            ->leftJoin('module.parent', 'parent')
            ->leftJoin('parent.parent', 'grandparent')
            ->where('e.statut = :statut AND (e.module = :module OR module.parent = :module2 OR parent.parent = :module3)')
            ->setParameter('statut', 'OUVERT')
            ->setParameter('module', $module)
            ->setParameter('module2', $module)
            ->setParameter('module3', $module)
            ->orderBy('grandparent.libelle')
            ->addOrderBy('parent.libelle')
            ->addOrderBy('module.libelle')
            ->addOrderBy('e.id');

        return $queryBuilder->getQuery()->getResult();
    }

    public function entreesFermeesParModule(Module $module)
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->leftJoin('e.commentaires', 'c')
            ->addSelect('c')
            ->leftJoin('e.images', 'i')
            ->addSelect('i')
            ->leftJoin('e.module', 'module')
            ->leftJoin('module.parent', 'parent')
            ->leftJoin('parent.parent', 'grandparent')
            ->where('e.statut = :statut OR e.statut = :statutFerme AND (e.module = :module OR module.parent = :module2 OR parent.parent = :module3)')
            ->setParameter('statut', 'ANNULE')
            ->setParameter('statutFerme', 'FERME')
            ->setParameter('module', $module)
            ->setParameter('module2', $module)
            ->setParameter('module3', $module)
            ->orderBy('grandparent.libelle')
            ->addOrderBy('parent.libelle')
            ->addOrderBy('module.libelle')
            ->addOrderBy('e.id');

        return $queryBuilder->getQuery()->getResult();
    }
}
