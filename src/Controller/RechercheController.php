<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Recherche;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gestion des recherches des entrées.
 *
 * @Route("/recherches/")
 */
class RechercheController extends AbstractController
{
    /**
     * liste des recherches.
     *
     * @Route("/")
     *
     * @Method({"GET"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository('RSSuiviDeProjetBundle:Recherche');
        $recherches = $repository->findByUser($this->getUser());

        return $this->render('RSSuiviDeProjetBundle:Recherche:partial.html.twig', array('recherches' => $recherches));
    }

    /**
     * ajout des paramètres de recherche.
     *
     * @Route("/addAjax")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function addAjaxAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        if ($request->isXmlHttpRequest()) {
            $recherche = new Recherche();
            $recherche->setUser($this->getUser());
            // Code de sauvegarde
            $parametre = $request->request->get('parametre');
            $recherche->setParametre($parametre);
            $entityManager->persist($recherche);
            $entityManager->flush();

            return new JsonResponse($parametre);
        }

        return $this->redirect($this->generateUrl('rs_suivideprojet_default_index'));
    }

    /**
     * Get a Recherche entity.
     *
     * @param Recherche $recherche
     *
     * @Route("/{recherche}/get")
     *
     * @Method({"GET"})
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAction(Recherche $recherche)
    {
        return new JsonResponse($recherche->getParametre());
    }

    /**
     * Deletes a Recherche entity.
     *
     * @param Recherche $recherche
     *
     * @Route("/{recherche}/delete")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Recherche $recherche)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($recherche);
        $entityManager->flush();
        $translator = $this->get('translator');
        $messageFlash = $translator->trans('filtre.delete_ok');
        $typeFlash = 'success';
        $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

        return $this->redirect($this->get('router')->generate('rs_suivideprojet_default_index'));
    }
}
