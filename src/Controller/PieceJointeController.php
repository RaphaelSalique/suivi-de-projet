<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\PieceJointe;
use App\Entity\Entree;
use App\Form\ImageType;
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
 * Gestion des pièces jointes des entrées.
 *
 * @Route("/{entree}/images")
 */
class PieceJointeController extends AbstractController
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
     * PieceJointeController constructor.
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
     * liste des images.
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
        $repository = $this->manager->getRepository(PieceJointe::class);
        $images = $repository->findByEntree($entree);

        return new Response($this->twig->render('PieceJointe/index.html.twig', array('images' => $images)));
    }

    /**
     * ajout d'un image.
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
        $image = new PieceJointe();
        $image->setEntree($entree);

        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entree->setMaj(new \DateTime());
            $entree->addImage($image);
            $this->manager->persist($image);
            $this->manager->persist($entree);
            $this->manager->flush();

            $messageFlash = $this->translator->trans('image.add_ok');
            $this->addFlash('success', $messageFlash);

            return $this->redirect($this->get('router')->generate('app_default_index'));
        }
        // On passe la méthode createView() du formulaire à la image afin qu'elle puisse afficher le formulaire toute seule
        return new Response($this->twig->render('PieceJointe/add.html.twig', array(
          'form' => $form->createView(),
        )));
    }

    /**
     * Displays a form to edit an existing PieceJointe entity.
     *
     * @Route("/{image}/edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Entree $entree
     * @param PieceJointe $image image/fonctionnalité à éditer
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editAction(Request $request, Entree $entree, PieceJointe $image): Response
    {
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager = $this->getDoctrine()->getManager();
            $entree->setMaj(new \DateTime());
            $this->manager->persist($entree);
            $this->manager->persist($image);
            $this->manager->flush();
            $messageFlash = $this->translator->trans('image.maj_ok');
            $this->addFlash('success', $messageFlash);

            return $this->redirect($this->get('router')->generate('app_default_index'));
        }
        // On passe la méthode createView() du formulaire à la image afin qu'elle puisse afficher le formulaire toute seule
        return new Response($this->twig->render('PieceJointe/edit.html.twig', array(
          'form' => $form->createView(),
          'image' => $image,
        )));
    }

    /**
     * Deletes a PieceJointe entity.
     *
     * @Route("/{image}/delete", methods={"GET", "POST"})
     *
     * @param Entree $entree
     * @param PieceJointe $image
     *
     * @return RedirectResponse
     */
    public function deleteAction(Entree $entree, PieceJointe $image): RedirectResponse
    {
        $this->manager = $this->getDoctrine()->getManager();
        $this->manager->remove($image);
        $entree->removeImage($image);
        $entree->setMaj(new \DateTime());
        $this->manager->persist($entree);
        $this->manager->flush();
        $messageFlash = $this->translator->trans('image.delete_ok');
        $this->addFlash('success', $messageFlash);

        return $this->redirect($this->get('router')->generate('app_default_index'));
    }
}
