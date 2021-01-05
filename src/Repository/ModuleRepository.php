<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
//use RS\UserBundle\Entity\User;

/**
 * ModuleRepository.
 */
class ModuleRepository extends EntityRepository
{
    public function modulesParents()
    {
        $queryBuilder = $this->createQueryBuilder('module');
        $queryBuilder->leftJoin('module.children', 'submodule')
        ->addSelect('submodule')
        ->leftJoin('submodule.children', 'subsubmodule')
        ->addSelect('subsubmodule')
        ->where('module.parent IS null')
        ->orderBy('module.libelle')
        ->addOrderBy('submodule.libelle')
        ->addOrderBy('subsubmodule.libelle');

        return $queryBuilder->getQuery()->getResult();
    }

//    public function modulesParUser(User $user)
//    {
//        $queryBuilder = $this->createQueryBuilder('module')
//        ->leftJoin('module.entrees', 'entree')
//        ->addSelect('entree')
//        ->leftJoin('module.children', 'submodule')
//        ->addSelect('submodule')
//        ->leftJoin('submodule.entrees', 'subentree')
//        ->addSelect('subentree')
//        ->leftJoin('submodule.children', 'subsubmodule')
//        ->addSelect('subsubmodule')
//        ->leftJoin('subsubmodule.entrees', 'subsubentree')
//        ->addSelect('subsubentree')
//        ->where('module.parent IS null AND (entree.statut = :statut AND (entree.assigne = :assigne OR entree.createur = :createur))
//         OR (subentree.statut = :substatut AND (subentree.assigne = :subassigne OR subentree.createur = :subcreateur))
//         OR (subsubentree.statut = :subsubstatut AND (subsubentree.assigne = :subsubassigne OR subsubentree.createur = :subsubcreateur))
//         ')
//        ->setParameter('statut', 'OUVERT')
//        ->setParameter('assigne', $user)
//        ->setParameter('createur', $user)
//        ->setParameter('substatut', 'OUVERT')
//        ->setParameter('subassigne', $user)
//        ->setParameter('subcreateur', $user)
//        ->setParameter('subsubstatut', 'OUVERT')
//        ->setParameter('subsubassigne', $user)
//        ->setParameter('subsubcreateur', $user)
//        ->orderBy('module.libelle');
//
//        return $queryBuilder->getQuery()->getResult();
//    }

    public function qbModulesPourTous()
    {
        $queryBuilder = $this->createQueryBuilder('module');
        $queryBuilder->leftJoin('module.children', 'submodule')
        ->addSelect('submodule')
        ->leftJoin('submodule.children', 'subsubmodule')
        ->addSelect('subsubmodule')
        ->leftJoin('subsubmodule.children', 'subsubchildren')
        ->addSelect('subsubchildren')
        ->orderBy('module.libelle');

        return $queryBuilder;
    }

    public function modulesOuvertsPourTous()
    {
        $queryBuilder = $this->createQueryBuilder('module');
        $queryBuilder->leftJoin('module.children', 'submodule')
        ->addSelect('submodule')
        ->leftJoin('module.entrees', 'entrees')
        ->addSelect('entrees')
        ->leftJoin('submodule.children', 'subsubmodule')
        ->addSelect('subsubmodule')
        ->leftJoin('submodule.entrees', 'subentrees')
        ->addSelect('subentrees')
        ->leftJoin('subsubmodule.children', 'subsubchildren')
        ->addSelect('subsubchildren')
        ->leftJoin('subsubmodule.entrees', 'subsubentrees')
        ->addSelect('subsubentrees')
        ->where('module.parent IS null AND (entrees.statut = :statut OR subentrees.statut = :substatut OR subsubentrees.statut = :subsubstatut)')
        ->setParameter('statut', 'OUVERT')
        ->setParameter('substatut', 'OUVERT')
        ->setParameter('subsubstatut', 'OUVERT')
        ->orderBy('module.libelle')
        ->addOrderBy('submodule.libelle')
        ->addOrderBy('subsubmodule.libelle');

        return $queryBuilder->getQuery()->getResult();
    }

    public function modulesFermesPourTous()
    {
        $queryBuilder = $this->createQueryBuilder('module');
        $queryBuilder->leftJoin('module.children', 'submodule')
        ->addSelect('submodule')
        ->leftJoin('module.entrees', 'entrees')
        ->addSelect('entrees')
        ->leftJoin('submodule.children', 'subsubmodule')
        ->addSelect('subsubmodule')
        ->leftJoin('submodule.entrees', 'subentrees')
        ->addSelect('subentrees')
        ->leftJoin('subsubmodule.children', 'subsubchildren')
        ->addSelect('subsubchildren')
        ->leftJoin('subsubmodule.entrees', 'subsubentrees')
        ->addSelect('subsubentrees')
        ->where('module.parent IS null AND (entrees.statut = :statut OR entrees.statut = :statutFerme OR subentrees.statut = :substatut OR subentrees.statut = :substatutFerme OR subsubentrees.statut = :subsubstatut OR subsubentrees.statut = :subsubstatutFerme)')
        ->setParameter('statut', 'ANNULE')
        ->setParameter('substatut', 'ANNULE')
        ->setParameter('subsubstatut', 'ANNULE')
        ->setParameter('statutFerme', 'FERME')
        ->setParameter('substatutFerme', 'FERME')
        ->setParameter('subsubstatutFerme', 'FERME')
        ->orderBy('module.libelle')
        ->addOrderBy('submodule.libelle')
        ->addOrderBy('subsubmodule.libelle');

        return $queryBuilder->getQuery()->getResult();
    }
}
