<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use App\Entity\Commentaire;
use App\Entity\Entree;
use App\Form\CommentaireEditType;

/**
 * Gestion des commentaires des entrées.
 *
 * @Route("/{entree}/commentaires/")
 */
class CommentaireController extends AbstractController
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
     * CommentaireController constructor.
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
     * liste des commentaires.
     *
     * @Route("/", methods={"GET"})
     *
     * @param Entree $entree
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(Entree $entree): Response
    {
        $repository = $this->manager->getRepository(Commentaire::class);
        $commentaires = $repository->findByEntree($entree);

        return new Response($this->twig->render('Commentaire/index.html.twig', array('commentaires' => $commentaires)));
    }

    /**
     * ajout d'un commentaire / fonctionnalite.
     *
     * @Route("/add", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Entree $entree
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function addAction(Request $request, Entree $entree): Response
    {
        $commentaire = new Commentaire();
        $commentaire->setEntree($entree);
        /** @var User $user */
        $user = $this->getUser();
        $commentaire->setUser($user);

        $form = $this->createForm(CommentaireEditType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entree->setMaj(new \DateTime());
            $entree->addCommentaire($commentaire);
            $this->manager->persist($commentaire);
            $this->manager->persist($entree);
            $this->manager->flush();

            $messageFlash = $this->translator->trans('commentaire.add_ok');
            $this->addFlash('success', $messageFlash);

            return $this->redirect($this->get('router')->generate('app_default_index'));
        }
        // On passe la méthode createView() du formulaire à la commentaire afin qu'elle puisse afficher le formulaire toute seule
        return new Response($this->twig->render('Commentaire/add.html.twig', array(
            'form' => $form->createView(),
        )));
    }

    /**
     * Displays a form to edit an existing Commentaire entity.
     *
     * @Route("/{commentaire}/edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Entree $entree
     * @param Commentaire $commentaire commentaire/fonctionnalité à éditer
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editAction(Request $request, Entree $entree, Commentaire $commentaire): Response
    {
        $form = $this->createForm(CommentaireEditType::class, $commentaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entree->setMaj(new \DateTime());
            $this->manager->persist($commentaire);
            $this->manager->persist($entree);
            $this->manager->flush();
            $messageFlash = $this->translator->trans('commentaire.maj_ok');
            $this->addFlash('success', $messageFlash);

            return $this->redirect($this->get('router')->generate('app_default_index'));
        }
        // On passe la méthode createView() du formulaire à la commentaire afin qu'elle puisse afficher le formulaire toute seule
        return new Response($this->twig->render('Commentaire/edit.html.twig', array(
          'form' => $form->createView(),
          'commentaire' => $commentaire,
        )));
    }

    /**
     * Deletes a Commentaire entity.
     *
     * @Route("/{commentaire}/delete", methods={"GET", "POST"})
     *
     * @param Entree $entree
     * @param Commentaire $commentaire
     *
     * @return RedirectResponse
     */
    public function deleteAction(Entree $entree, Commentaire $commentaire): RedirectResponse
    {
        $entree->setMaj(new \DateTime());
        $this->manager->remove($commentaire);
        $entree->removeCommentaire($commentaire);
        $this->manager->flush();
        $messageFlash = $this->translator->trans('commentaire.delete_ok');
        $this->addFlash('success', $messageFlash);

        return $this->redirect($this->get('router')->generate('app_default_index'));
    }
}
