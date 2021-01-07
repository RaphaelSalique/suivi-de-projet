<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Recherche;
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
 * Gestion des recherches des entrées.
 *
 * @Route("/recherches/")
 */
class RechercheController extends AbstractController
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
     * RechercheController constructor.
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
     * liste des recherches.
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
        $repository = $this->manager->getRepository(Recherche::class);
        $recherches = $repository->findByUser($this->getUser());

        return new Response($this->twig->render('Recherche/partial.html.twig', array('recherches' => $recherches)));
    }

    /**
     * ajout des paramètres de recherche.
     *
     * @Route("/addAjax", methods={"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function addAjaxAction(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $recherche = new Recherche();
            /** @var User $suer */
            $user = $this->getUser();
            $recherche->setUser($user);
            // Code de sauvegarde
            $parametre = $request->request->get('parametre');
            $recherche->setParametre($parametre);
            $this->manager->persist($recherche);
            $this->manager->flush();

            return new JsonResponse($parametre);
        }

        return $this->redirect($this->generateUrl('app_default_index'));
    }

    /**
     * Get a Recherche entity.
     *
     * @param Recherche $recherche
     *
     * @Route("/{recherche}/get", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getAction(Recherche $recherche): JsonResponse
    {
        return new JsonResponse($recherche->getParametre());
    }

    /**
     * Deletes a Recherche entity.
     *
     * @param Recherche $recherche
     *
     * @Route("/{recherche}/delete", methods={"GET", "POST"})
     *
     * @return RedirectResponse
     */
    public function deleteAction(Recherche $recherche): RedirectResponse
    {
        $this->manager->remove($recherche);
        $this->manager->flush();
        $messageFlash = $this->translator->trans('filtre.delete_ok');
        $this->addFlash('success', $messageFlash);

        return $this->redirect($this->get('router')->generate('app_default_index'));
    }
}
