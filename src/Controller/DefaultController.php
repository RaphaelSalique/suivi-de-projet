<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Eko\FeedBundle\Feed\FeedManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Entree;
use App\Entity\ModuleFonctionnaliteType;
use App\Form\EntreeType;
use App\Form\EntreeEditType;
use App\DBAL\Types\StatutType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Accueil du suivi de projet.
 *
 * @Route("/")
 */
class DefaultController extends AbstractController
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
     * @var FeedManager
     */
    private $feedManager;
    /**
     * @var Environment
     */
    private $twig;

    /**
     * DefaultController constructor.
     * @param EntityManagerInterface $manager
     * @param TranslatorInterface $translator
     * @param FeedManager $feedManager
     * @param Environment $twig
     */
    public function __construct(EntityManagerInterface $manager, TranslatorInterface $translator, FeedManager $feedManager, Environment $twig)
    {
        $this->manager = $manager;
        $this->translator = $translator;
        $this->feedManager = $feedManager;
        $this->twig = $twig;
    }

    /**
     * page par défaut.
     *
     * @Route("/", methods={"GET"})*
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(): Response
    {
        $user = $this->getUser();
        if ((!empty($user)) && (($user->isAdmin()))) {
            $repository = $this->manager->getRepository(Entree::class);
            $entrees = $repository->myFindAll();

            return new Response($this->twig->render('Default/index.html.twig', array('entrees' => $entrees)));
        }

        return $this->redirect($this->generateUrl('app_utilisateur_affichermodulesparuser', array('user' => $user->getId())), 301);
    }

    /**
     * ajout d'une entrée.
     *
     * @Route("/add", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function addAction(Request $request): Response
    {
        $entree = new Entree();
        /** @var User $createur */
        $createur = $this->getUser();
        $entree->setAssigne($createur);
        $entree->setCreateur($createur);
        $entree->setStatut(StatutType::OUVERT);

        $form = $this->createForm(EntreeType::class, $entree);
        $view = $form->createView();
        usort(
            $view->children['module']->vars['choices'],
            function ($premier, $deuxieme) {
                return strcasecmp($premier->label, $deuxieme->label);
            }
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($entree->getCommentaires() as $commentaire) {
                $commentaire->setUser($createur);
                $commentaire->setEntree($entree);
            }
            $this->manager->persist($entree);
            $this->manager->flush();

            $messageFlash = $this->translator->trans('entree.add_ok');
            $this->addFlash('success', $messageFlash);

            return $this->redirect($this->get('router')->generate('app_default_index'));
        }
        // On passe la méthode createView() du formulaire à la entree afin qu'elle puisse afficher le formulaire toute seule
        return new Response($this->twig->render('Entree/add.html.twig', array(
          'form' => $form->createView(),
        )));
    }

    /**
     * @Route("/{entree}/edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Entree $entree entree/fonctionnalité à éditer
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editAction(Request $request, Entree $entree): Response
    {
        $form = $this->createForm(EntreeEditType::class, $entree);
        $view = $form->createView();
        usort(
            $view->children['module']->vars['choices'],
            function ($premier, $deuxieme) {
                return strcasecmp($premier->label, $deuxieme->label);
            }
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entree->setMaj(new \DateTime());
            $this->manager->persist($entree);
            $this->manager->flush();
            $messageFlash = $this->translator->trans('entree.maj_ok');
            $this->addFlash('success', $messageFlash);

            return $this->redirect($this->get('router')->generate('app_default_index'));
        }
        // On passe la méthode createView() du formulaire à la entree afin qu'elle puisse afficher le formulaire toute seule
        return new Response($this->twig->render('Entree/edit.html.twig', array(
          'form' => $form->createView(),
          'entree' => $entree,
        )));
    }

    /**
     * Displays a form to edit an existing Entree entity from a module.
     *
     * @Route("/{entree}/editFromModule/{module}", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param ModuleFonctionnaliteType $module module de provenance
     * @param Entree $entree entree/fonctionnalité à éditer
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editFromModuleAction(Request $request, ModuleFonctionnaliteType $module, Entree $entree): Response
    {
        $form = $this->createForm(EntreeEditType::class, $entree);
        $view = $form->createView();
        usort(
            $view->children['module']->vars['choices'],
            function ($premier, $deuxieme) {
                return strcasecmp($premier->label, $deuxieme->label);
            }
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entree->setMaj(new \DateTime());
            $this->manager->persist($entree);
            $this->manager->flush();
            $messageFlash = $this->translator->trans('entree.maj_ok');
            $this->addFlash('success', $messageFlash);

            return $this->redirect($this->get('router')->generate('app_modulefonctionnalitetype_afficherdetailmodulestoususers', array('module' => $module->getId())));
        }
        // On passe la méthode createView() du formulaire à la entree afin qu'elle puisse afficher le formulaire toute seule
        return new Response($this->twig->render('Entree/edit.html.twig', array(
          'form' => $form->createView(),
          'entree' => $entree,
        )));
    }

    /**
     * Deletes a Entree entity.
     *
     * @param Entree $entree
     *
     * @Route("/{entree}/delete", methods={"GET", "POST"})
     *
     * @return RedirectResponse
     */
    public function deleteAction(Entree $entree): RedirectResponse
    {
        foreach ($entree->getCommentaires() as $commentaire) {
            $entree->removeCommentaire($commentaire);
            $this->manager->remove($commentaire);
        }

        $this->manager->remove($entree);
        $this->manager->flush();
        $messageFlash = $this->translator->trans('entree.delete_ok');
        $this->addFlash('success', $messageFlash);

        return $this->redirect($this->get('router')->generate('app_default_index'));
    }

    /**
     * Deletes a Entree entity from a Module.
     *
     * @param Entree                   $entree
     * @param ModuleFonctionnaliteType $module module de provenance
     *
     * @Route("/{entree}/deleteFromModule/{module}", methods={"GET", "POST"})
     *
     * @return RedirectResponse
     */
    public function deleteFromModuleAction(ModuleFonctionnaliteType $module, Entree $entree): RedirectResponse
    {
        foreach ($entree->getCommentaires() as $commentaire) {
            $entree->removeCommentaire($commentaire);
            $this->manager->remove($commentaire);
        }

        $this->manager->remove($entree);
        $this->manager->flush();
        $messageFlash = $this->translator->trans('entree.delete_ok');
        $this->addFlash('success', $messageFlash);

        return $this->redirect($this->get('router')->generate('app_modulefonctionnalitetype_afficherdetailmodulestoususers', array('module' => $module->getId())));
    }

    /**
     * Generate the entree feed.
     *
     * @Route("feed", methods={"GET", "POST"})
     *
     * @return Response XML Feed
     */
    public function feedAction(): Response
    {
        $entrees = $this->manager->getRepository(Entree::class)->findAll();

        $feed = $this->feedManager->get('entree');
        $feed->addFromArray($entrees);

        return new Response($feed->render('atom')); // 'atom' / 'rss'
    }
}
