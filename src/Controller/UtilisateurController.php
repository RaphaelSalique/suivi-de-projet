<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ModuleFonctionnaliteType as Module;
use App\Entity\User;

/**
 * Gestion des users.
 *
 * @Route("/utilisateurs")
 */
class UtilisateurController extends AbstractController
{
    /**
     * liste des users.
     *
     * @Route("/", methods={"GET"})
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityRepository = $entityManager->getRepository('RSUserBundle:User');
        $users = $entityRepository->findAll();

        return $this->render('RSSuiviDeProjetBundle:Utilisateur:partial.html.twig', array('users' => $users));
    }

    /**
     * liste des modules avec le nombre d'entrées assignées ou créées par un utilisateur donné.
     *
     * @Route("/{user}", methods={"GET"})
     *
     * @param User $user
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function afficherModulesParUser(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityRepository = $entityManager->getRepository('RSSuiviDeProjetBundle:ModuleFonctionnaliteType');
        $modules = $entityRepository->modulesParUser($user);

        return $this->render('RSSuiviDeProjetBundle:Utilisateur:modules.html.twig', array('modules' => $modules, 'user' => $user));
    }

    /**
     * liste des entrées assignées ou créées par un utilisateur donné pour un module donné.
     *
     * @Route("/{user}/{module}", methods={"GET"})
     *
     * @param User   $user
     * @param Module $module
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function afficherDetailModulesParUser(User $user, Module $module)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityRepository = $entityManager->getRepository('RSSuiviDeProjetBundle:Entree');
        $entrees = $entityRepository->entreesParUserEtParModule($user, $module);

        return $this->render('RSSuiviDeProjetBundle:Utilisateur:entrees_module.html.twig', array('module' => $module, 'entrees' => $entrees, 'user' => $user));
    }
}
