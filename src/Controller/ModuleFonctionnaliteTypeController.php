<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ModuleFonctionnaliteType;
use App\Form\ModuleFonctionnaliteTypeType;
use App\Form\ModuleFonctionnaliteTypeEditType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gestion des modules / fonctionnalités.
 *
 * @Route("/modules")
 */
class ModuleFonctionnaliteTypeController extends AbstractController
{
    /**
     * liste des modules / fonctionnalités.
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
        $repository = $entityManager->getRepository('RSSuiviDeProjetBundle:ModuleFonctionnaliteType');
        $modules = $repository->modulesParents();

        return $this->render('RSSuiviDeProjetBundle:ModuleFonctionnaliteType:index.html.twig', array('modules' => $modules));
    }

    /**
     * liste des modules ouverts avec le nombre d'entrées.
     *
     * @Route("/liste")
     *
     * @Method({"GET"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function listeModulesAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityRepository = $entityManager->getRepository('RSSuiviDeProjetBundle:ModuleFonctionnaliteType');
        $modules = $entityRepository->modulesOuvertsPourTous();

        return $this->render('RSSuiviDeProjetBundle:ModuleFonctionnaliteType:modules_tous_users.html.twig', array('modules' => $modules));
    }

    /**
     * liste des entrées assignées ou créées pour un module donné.
     *
     * @Route("/liste/{module}")
     *
     * @Method({"GET"})
     *
     * @param ModuleFonctionnaliteType $module
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function afficherDetailModulesTousUsers(ModuleFonctionnaliteType $module)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityRepository = $entityManager->getRepository('RSSuiviDeProjetBundle:Entree');
        $entrees = $entityRepository->entreesOuvertesParModule($module);

        return $this->render('RSSuiviDeProjetBundle:ModuleFonctionnaliteType:entrees_module.html.twig', array('module' => $module, 'entrees' => $entrees));
    }

    /**
     * ajout d'un module / fonctionnalite.
     *
     * @Route("/add")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function addAction()
    {
        $module = new ModuleFonctionnaliteType();

        $form = $this->createForm(new ModuleFonctionnaliteTypeType(), $module);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($module);
                $entityManager->flush();

                $translator = $this->get('translator');
                $messageFlash = $translator->trans('module.add_ok', array('%module%' => $module->getLibelle()));
                $typeFlash = 'success';
                $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

                return $this->redirect($this->get('router')->generate('rs_suivideprojet_modulefonctionnalitetype_index'));
            }
        }
        // On passe la méthode createView() du formulaire à la module afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('RSSuiviDeProjetBundle:ModuleFonctionnaliteType:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * ajout d'un module / fonctionnalite.
     *
     * @param ModuleFonctionnaliteType $module module/fonctionnalité à éditer
     *
     * @Route("/{module}/addsubmodule")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function addSubmoduleAction(ModuleFonctionnaliteType $module)
    {
        $submodule = new ModuleFonctionnaliteType();
        $module->addChild($submodule);

        $form = $this->createForm(new ModuleFonctionnaliteTypeEditType(), $submodule);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($submodule);
                $entityManager->flush();

                $translator = $this->get('translator');
                $messageFlash = $translator->trans('module.submodule.add_ok', array('%module%' => $submodule->getLibelle()));
                $typeFlash = 'success';
                $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

                return $this->redirect($this->get('router')->generate('rs_suivideprojet_modulefonctionnalitetype_index'));
            }
        }
        // On passe la méthode createView() du formulaire à la module afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('RSSuiviDeProjetBundle:ModuleFonctionnaliteType:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ModuleFonctionnaliteType entity.
     *
     * @param ModuleFonctionnaliteType $module module/fonctionnalité à éditer
     *
     * @Route("/{module}/edit")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function editAction(ModuleFonctionnaliteType $module)
    {
        $form = $this->createForm(new ModuleFonctionnaliteTypeEditType(), $module);
        $view = $form->createView();
        usort(
            $view->children['parent']->vars['choices'],
            function ($premier, $deuxieme) {
                return strcasecmp($premier->label, $deuxieme->label);
            }
        );

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($module);
                $entityManager->flush();
                $translator = $this->get('translator');
                $messageFlash = $translator->trans('module.maj_ok', array('%module%' => $module->getLibelle()));
                $typeFlash = 'success';
                $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

                return $this->redirect($this->get('router')->generate('rs_suivideprojet_modulefonctionnalitetype_index'));
            }
        }
        // On passe la méthode createView() du formulaire à la module afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('RSSuiviDeProjetBundle:ModuleFonctionnaliteType:edit.html.twig', array(
            'module' => $module,
            'form' => $view,
        ));
    }

    /**
     * Deletes a ModuleFonctionnaliteType entity.
     *
     * @param ModuleFonctionnaliteType $module
     *
     * @Route("/{module}/delete")
     *
     * @Method({"GET", "POST"})
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(ModuleFonctionnaliteType $module)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($module);
        $entityManager->flush();
        $translator = $this->get('translator');
        $messageFlash = $translator->trans('module.delete_ok', array('%module%' => $module->getLibelle()));
        $typeFlash = 'success';
        $this->get('session')->getFlashBag()->add($typeFlash, $messageFlash);

        return $this->redirect($this->get('router')->generate('rs_suivideprojet_modulefonctionnalitetype_index'));
    }

    /**
     * liste des modules annulés ou fermés avec le nombre d'entrées.
     *
     * @Route("/archives")
     *
     * @Method({"GET"})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function listeModulesArchivesAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityRepository = $entityManager->getRepository('RSSuiviDeProjetBundle:ModuleFonctionnaliteType');
        $modules = $entityRepository->modulesFermesPourTous();

        return $this->render('RSSuiviDeProjetBundle:ModuleFonctionnaliteType:archives_tous_users.html.twig', array('modules' => $modules));
    }

    /**
     * liste des entrées assignées ou créées pour un module donné.
     *
     * @Route("/archives/{module}")
     *
     * @Method({"GET"})
     *
     * @param ModuleFonctionnaliteType $module
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function afficherDetailModulesArchivesTousUsers(ModuleFonctionnaliteType $module)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityRepository = $entityManager->getRepository('RSSuiviDeProjetBundle:Entree');
        $entrees = $entityRepository->entreesFermeesParModule($module);

        return $this->render('RSSuiviDeProjetBundle:ModuleFonctionnaliteType:entrees_module.html.twig', array('module' => $module, 'entrees' => $entrees, 'archive' => true));
    }
}
