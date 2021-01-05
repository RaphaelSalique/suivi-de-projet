<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\PieceJointe;
use App\Entity\Entree;
use App\Form\ImageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gestion des pièces jointes des entrées.
 *
 * @Route("/{entree}/images")
 */
class PieceJointeController extends AbstractController
{
    /**
     * liste des images.
     *
     * @Route("/")
     *
     * @Method({"GET"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Entree $entree)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository('RSSuiviDeProjetBundle:PieceJointe');
        $images = $repository->findByEntree($entree);

        return $this->render('RSSuiviDeProjetBundle:PieceJointe:index.html.twig', array('images' => $images));
    }

    /**
     * ajout d'un image.
     *
     * @Route("/add")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Entree $entree)
    {
        $image = new PieceJointe();
        $image->setEntree($entree);

        $form = $this->createForm(new ImageType(), $image);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entree->setMaj(new \DateTime());
                $entree->addImage($image);
                $entityManager->persist($image);
                $entityManager->persist($entree);
                $entityManager->flush();

                $translator = $this->get('translator');
                $messageFlash = $translator->trans('image.add_ok');
                $typeFlash = 'success';
                $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

                return $this->redirect($this->get('router')->generate('rs_suivideprojet_default_index'));
            }
        }
        // On passe la méthode createView() du formulaire à la image afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('RSSuiviDeProjetBundle:PieceJointe:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PieceJointe entity.
     *
     * @param PieceJointe $image image/fonctionnalité à éditer
     *
     * @Route("/{image}/edit")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Entree $entree, PieceJointe $image)
    {
        $form = $this->createForm(new ImageType(), $image);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entree->setMaj(new \DateTime());
                $entityManager->persist($entree);
                $entityManager->persist($image);
                $entityManager->flush();
                $translator = $this->get('translator');
                $messageFlash = $translator->trans('image.maj_ok');
                $typeFlash = 'success';
                $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

                return $this->redirect($this->get('router')->generate('rs_suivideprojet_default_index'));
            }
        }
        // On passe la méthode createView() du formulaire à la image afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('RSSuiviDeProjetBundle:PieceJointe:edit.html.twig', array(
            'image' => $image,
            'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a PieceJointe entity.
     *
     * @param PieceJointe $image
     *
     * @Route("/{image}/delete")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Entree $entree, PieceJointe $image)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($image);
        $entree->removePieceJointe($image);
        $entree->setMaj(new \DateTime());
        $entityManager->persist($entree);
        $entityManager->flush();
        $translator = $this->get('translator');
        $messageFlash = $translator->trans('image.delete_ok');
        $typeFlash = 'success';
        $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

        return $this->redirect($this->get('router')->generate('rs_suivideprojet_default_index'));
    }
}
