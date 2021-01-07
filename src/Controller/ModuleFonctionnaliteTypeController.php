<?php

namespace App\Controller;

use App\Entity\Entree;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ModuleFonctionnaliteType;
use App\Form\ModuleFonctionnaliteTypeType;
use App\Form\ModuleFonctionnaliteTypeEditType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Gestion des modules / fonctionnalités.
 *
 * @Route("/modules")
 */
class ModuleFonctionnaliteTypeController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var Environment
     */
    private $twig;

    /**
     * ModuleFonctionnaliteTypeController constructor.
     * @param EntityManagerInterface $manager
     * @param TranslatorInterface $translator
     * @param Environment $twig
     */
    public function __construct(EntityManagerInterface $manager, TranslatorInterface $translator, Environment $twig)
    {
        $this->manager = $manager;
        $this->translator = $translator;
        $this->twig = $twig;
    }

    /**
     * liste des modules / fonctionnalités.
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
        $repository = $this->manager->getRepository(ModuleFonctionnaliteType::class);
        $modules = $repository->modulesParents();

        return new Response($this->twig->render('ModuleFonctionnaliteType/index.html.twig', array('modules' => $modules)));
    }

    /**
     * liste des modules ouverts avec le nombre d'entrées.
     *
     * @Route("/liste", methods={"GET"})
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function listeModulesAction(): Response
    {
        $entityRepository = $this->manager->getRepository(ModuleFonctionnaliteType::class);
        $modules = $entityRepository->modulesOuvertsPourTous();

        return new Response($this->twig->render('ModuleFonctionnaliteType/modules_tous_users.html.twig', array('modules' => $modules)));
    }

    /**
     * liste des entrées assignées ou créées pour un module donné.
     *
     * @Route("/liste/{module}", methods={"GET"})
     *
     * @param ModuleFonctionnaliteType $module
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function afficherDetailModulesTousUsers(ModuleFonctionnaliteType $module): Response
    {
        $entityRepository = $this->manager->getRepository(Entree::class);
        $entrees = $entityRepository->entreesOuvertesParModule($module);

        return new Response($this->twig->render('ModuleFonctionnaliteType/entrees_module.html.twig', array('module' => $module, 'entrees' => $entrees)));
    }

    /**
     * ajout d'un module / fonctionnalite.
     *
     * @Route("/add", methods={"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function addAction(Request $request): Response
    {
        $module = new ModuleFonctionnaliteType();

        $form = $this->createForm(new ModuleFonctionnaliteTypeType(), $module);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($module);
            $this->manager->flush();

            $messageFlash = $this->translator->trans('module.add_ok', array('%module%' => $module->getLibelle()));
            $this->addFlash('success', $messageFlash);

            return $this->redirect($this->get('router')->generate('app_modulefonctionnalitetype_index'));
        }
        // On passe la méthode createView() du formulaire à la module afin qu'elle puisse afficher le formulaire toute seule
        return new Response($this->twig->render('ModuleFonctionnaliteType/add.html.twig', array(
          'form' => $form->createView(),
        )));
    }

    /**
     * ajout d'un module / fonctionnalite.
     *
     * @param Request $request
     * @param ModuleFonctionnaliteType $module module/fonctionnalité à éditer
     *
     * @Route("/{module}/addsubmodule", methods={"GET", "POST"})
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function addSubmoduleAction(Request $request, ModuleFonctionnaliteType $module): Response
    {
        $submodule = new ModuleFonctionnaliteType();
        $module->addChild($submodule);

        $form = $this->createForm(new ModuleFonctionnaliteTypeEditType(), $submodule);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($submodule);
            $this->manager->flush();

            $messageFlash = $this->translator->trans('module.submodule.add_ok', array('%module%' => $submodule->getLibelle()));
            $this->addFlash('success', $messageFlash);

            return $this->redirect($this->get('router')->generate('app_modulefonctionnalitetype_index'));
        }
        // On passe la méthode createView() du formulaire à la module afin qu'elle puisse afficher le formulaire toute seule
        return new Response($this->twig->render('ModuleFonctionnaliteType/add.html.twig', array(
          'form' => $form->createView(),
        )));
    }

    /**
     * Displays a form to edit an existing ModuleFonctionnaliteType entity.
     *
     * @param Request $request
     * @param ModuleFonctionnaliteType $module module/fonctionnalité à éditer
     *
     * @Route("/{module}/edit", methods={"GET", "POST"})
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editAction(Request $request, ModuleFonctionnaliteType $module): Response
    {
        $form = $this->createForm(new ModuleFonctionnaliteTypeEditType(), $module);
        $view = $form->createView();
        usort(
            $view->children['parent']->vars['choices'],
            function ($premier, $deuxieme) {
                return strcasecmp($premier->label, $deuxieme->label);
            }
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($module);
            $this->manager->flush();
            $messageFlash = $this->translator->trans('module.maj_ok', array('%module%' => $module->getLibelle()));
            $this->addFlash('success', $messageFlash);

            return $this->redirect($this->get('router')->generate('app_modulefonctionnalitetype_index'));
        }
        // On passe la méthode createView() du formulaire à la module afin qu'elle puisse afficher le formulaire toute seule
        return new Response($this->twig->render('ModuleFonctionnaliteType/edit.html.twig', array(
            'form' => $form->createView(),
            'module' => $module,
        )));
    }

    /**
     * Deletes a ModuleFonctionnaliteType entity.
     *
     * @param ModuleFonctionnaliteType $module
     *
     * @Route("/{module}/delete", methods={"GET", "POST"})
     *
     * @return RedirectResponse
     */
    public function deleteAction(ModuleFonctionnaliteType $module): RedirectResponse
    {
        $this->manager->remove($module);
        $this->manager->flush();
        $messageFlash = $this->translator->trans('module.delete_ok', array('%module%' => $module->getLibelle()));
        $this->addFlash('success', $messageFlash);

        return $this->redirect($this->get('router')->generate('app_modulefonctionnalitetype_index'));
    }

    /**
     * liste des modules annulés ou fermés avec le nombre d'entrées.
     *
     * @Route("/archives", methods={"GET"})
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function listeModulesArchivesAction(): Response
    {
        $entityRepository = $this->manager->getRepository(ModuleFonctionnaliteType::class);
        $modules = $entityRepository->modulesFermesPourTous();

        return new Response($this->twig->render('ModuleFonctionnaliteType/archives_tous_users.html.twig', array('modules' => $modules)));
    }

    /**
     * liste des entrées assignées ou créées pour un module donné.
     *
     * @Route("/archives/{module}", methods={"GET"})
     *
     * @param ModuleFonctionnaliteType $module
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function afficherDetailModulesArchivesTousUsers(ModuleFonctionnaliteType $module): Response
    {
        $entityRepository = $this->manager->getRepository(Entree::class);
        $entrees = $entityRepository->entreesFermeesParModule($module);

        return new Response($this->twig->render('ModuleFonctionnaliteType/entrees_module.html.twig', array('module' => $module, 'entrees' => $entrees, 'archive' => true)));
    }
}
