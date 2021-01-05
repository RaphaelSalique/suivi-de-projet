<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Entree;
use App\Entity\ModuleFonctionnaliteType;
use App\Form\EntreeType;
use App\Form\EntreeEditType;
use App\DBAL\Types\StatutType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Accueil du suivi de projet.
 *
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * page par défaut.
     *
     * @Route("/")
     *
     * @Method({"GET"})
     *
     * @return Response
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if (!is_object($user)) {
            return $this->redirect($this->generateUrl('rs_users_login'));
        }
        if (($user->getId() == 1) || ($user->getId() == 2)) {
            $entityManager = $this->getDoctrine()->getManager();
            $repository = $entityManager->getRepository('RSSuiviDeProjetBundle:Entree');
            $entrees = $repository->myFindAll();

            return $this->render('RSSuiviDeProjetBundle:Default:index.html.twig', array('entrees' => $entrees));
        }

        return $this->redirect($this->generateUrl('rs_suivideprojet_utilisateur_affichermodulesparuser', array('user' => $user->getId())), 301);
    }

    /**
     * ajout d'une entrée.
     *
     * @Route("/add")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function addAction()
    {
        $entree = new Entree();
        $createur = $this->getUser();
        $entree->setAssigne($createur);
        $entree->setCreateur($createur);
        $entree->setStatut(StatutType::OUVERT);

        $form = $this->createForm(new EntreeType(), $entree);
        $view = $form->createView();
        usort(
            $view->children['module']->vars['choices'],
            function ($premier, $deuxieme) {
                return strcasecmp($premier->label, $deuxieme->label);
            }
        );

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                foreach ($entree->getCommentaires() as $commentaire) {
                    $commentaire->setUser($createur);
                    $commentaire->setEntree($entree);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($entree);
                $entityManager->flush();

                $translator = $this->get('translator');
                $messageFlash = $translator->trans('entree.add_ok');
                $typeFlash = 'success';
                $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

                return $this->redirect($this->get('router')->generate('rs_suivideprojet_default_index'));
            }
        }
        // On passe la méthode createView() du formulaire à la entree afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('RSSuiviDeProjetBundle:Entree:add.html.twig', array(
            'form' => $view,
        ));
    }

    /**
     * Displays a form to edit an existing Entree entity.
     *
     * @param Entree $entree entree/fonctionnalité à éditer
     *
     * @Route("/{entree}/edit")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Entree $entree)
    {
        $form = $this->createForm(new EntreeEditType(), $entree);
        $view = $form->createView();
        usort(
            $view->children['module']->vars['choices'],
            function ($premier, $deuxieme) {
                return strcasecmp($premier->label, $deuxieme->label);
            }
        );

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $entree->setMaj(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($entree);
                $entityManager->flush();
                $translator = $this->get('translator');
                $messageFlash = $translator->trans('entree.maj_ok');
                $typeFlash = 'success';
                $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

                return $this->redirect($this->get('router')->generate('rs_suivideprojet_default_index'));
            }
        }
        // On passe la méthode createView() du formulaire à la entree afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('RSSuiviDeProjetBundle:Entree:edit.html.twig', array(
            'entree' => $entree,
            'form' => $view,
        ));
    }

    /**
     * Displays a form to edit an existing Entree entity from a module.
     *
     * @param Entree                   $entree entree/fonctionnalité à éditer
     * @param ModuleFonctionnaliteType $module module de provenance
     *
     * @Route("/{entree}/editFromModule/{module}")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function editFromModuleAction(ModuleFonctionnaliteType $module, Entree $entree)
    {
        $form = $this->createForm(new EntreeEditType(), $entree);
        $view = $form->createView();
        usort(
            $view->children['module']->vars['choices'],
            function ($premier, $deuxieme) {
                return strcasecmp($premier->label, $deuxieme->label);
            }
        );

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $entree->setMaj(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($entree);
                $entityManager->flush();
                $translator = $this->get('translator');
                $messageFlash = $translator->trans('entree.maj_ok');
                $typeFlash = 'success';
                $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

                return $this->redirect($this->get('router')->generate('rs_suivideprojet_modulefonctionnalitetype_afficherdetailmodulestoususers', array('module' => $module->getId())));
            }
        }
        // On passe la méthode createView() du formulaire à la entree afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('RSSuiviDeProjetBundle:Entree:edit.html.twig', array(
            'entree' => $entree,
            'form' => $view,
        ));
    }

    /**
     * Deletes a Entree entity.
     *
     * @param Entree $entree
     *
     * @Route("/{entree}/delete")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Entree $entree)
    {
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($entree->getCommentaires() as $commentaire) {
            $entree->removeCommentaire($commentaire);
            $entityManager->remove($commentaire);
        }

        $entityManager->remove($entree);
        $entityManager->flush();
        $translator = $this->get('translator');
        $messageFlash = $translator->trans('entree.delete_ok');
        $typeFlash = 'success';
        $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

        return $this->redirect($this->get('router')->generate('rs_suivideprojet_default_index'));
    }

    /**
     * Deletes a Entree entity from a Module.
     *
     * @param Entree                   $entree
     * @param ModuleFonctionnaliteType $module module de provenance
     *
     * @Route("/{entree}/deleteFromModule/{module}")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteFromModuleAction(ModuleFonctionnaliteType $module, Entree $entree)
    {
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($entree->getCommentaires() as $commentaire) {
            $entree->removeCommentaire($commentaire);
            $entityManager->remove($commentaire);
        }

        $entityManager->remove($entree);
        $entityManager->flush();
        $translator = $this->get('translator');
        $messageFlash = $translator->trans('entree.delete_ok');
        $typeFlash = 'success';
        $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

        return $this->redirect($this->get('router')->generate('rs_suivideprojet_modulefonctionnalitetype_afficherdetailmodulestoususers', array('module' => $module->getId())));
    }

    /**
     * Generate the entree feed.
     *
     * @Route("feed")
     *
     * @Method({"GET", "POST"})
     *
     * @return Response XML Feed
     */
    public function feedAction()
    {
        $entrees = $this->getDoctrine()->getRepository('RSSuiviDeProjetBundle:Entree')->findAll();

        $feed = $this->get('eko_feed.feed.manager')->get('entree');
        $feed->addFromArray($entrees);

        return new Response($feed->render('atom')); // 'atom' / 'rss'
    }
}
