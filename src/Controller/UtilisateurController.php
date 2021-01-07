<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Repository\EntreeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ModuleFonctionnaliteType as Module;
use App\Entity\User;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Gestion des users.
 *
 * @Route("/utilisateurs")
 */
class UtilisateurController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var Environment
     */
    private $twig;

    /**
     * UtilisateurController constructor.
     * @param EntityManagerInterface $manager
     * @param Environment $twig
     */
    public function __construct(EntityManagerInterface $manager, Environment $twig)
    {
        $this->manager = $manager;
        $this->twig = $twig;
    }

    /**
     * liste des users.
     *
     * @Route("/", methods={"GET"})
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(): Response
    {
        $entityRepository = $this->manager->getRepository('RSUserBundle:User');
        $users = $entityRepository->findAll();

        return new Response($this->twig->render('Utilisateur/partial.html.twig', array('users' => $users)));
    }

    /**
     * liste des modules avec le nombre d'entrées assignées ou créées par un utilisateur donné.
     *
     * @Route("/{user}", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function afficherModulesParUser(User $user)
    {
        $this->manager = $this->getDoctrine()->getManager();
        $entityRepository = $this->manager->getRepository(Module::class);
        $modules = $entityRepository->modulesParUser($user);

        return new Response($this->twig->render('Utilisateur/modules.html.twig', array('modules' => $modules, 'user' => $user)));
    }

    /**
     * liste des entrées assignées ou créées par un utilisateur donné pour un module donné.
     *
     * @Route("/{user}/{module}", methods={"GET"})
     *
     * @param User   $user
     * @param Module $module
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function afficherDetailModulesParUser(User $user, Module $module)
    {
        $this->manager = $this->getDoctrine()->getManager();
        /** @var EntreeRepository $entityRepository */
        $entityRepository = $this->manager->getRepository(Entree::class);
        $entrees = $entityRepository->entreesParUserEtParModule($user, $module);

        return new Response($this->twig->render('Utilisateur/entrees_module.html.twig', array('module' => $module, 'entrees' => $entrees, 'user' => $user)));
    }
}
