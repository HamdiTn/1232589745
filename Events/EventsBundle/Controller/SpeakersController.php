<?php

namespace EventsBundle\Controller;

use EventsBundle\Entity\Speakers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Speaker controller.
 *
 * @Route("speakers")
 */
class SpeakersController extends Controller
{
    /**
     * Lists all speaker entities.
     *
     * @Route("/", name="speakers_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $speakers = $em->getRepository('EventsBundle:Speakers')->findAll();

        return $this->render('speakers/index.html.twig', array(
            'speakers' => $speakers,
        ));
    }

    /**
     * Creates a new speaker entity.
     *
     * @Route("/new", name="speakers_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $speaker = new Speakers();
        $form = $this->createForm('EventsBundle\Form\SpeakersType', $speaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($speaker);
            $em->flush();

            return $this->redirectToRoute('speakers_show', array('id' => $speaker->getId()));
        }

        return $this->render('speakers/new.html.twig', array(
            'speaker' => $speaker,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a speaker entity.
     *
     * @Route("/{id}", name="speakers_show")
     * @Method("GET")
     */
    public function showAction(Speakers $speaker)
    {
        $deleteForm = $this->createDeleteForm($speaker);

        return $this->render('speakers/show.html.twig', array(
            'speaker' => $speaker,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing speaker entity.
     *
     * @Route("/{id}/edit", name="speakers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Speakers $speaker)
    {
        $deleteForm = $this->createDeleteForm($speaker);
        $editForm = $this->createForm('EventsBundle\Form\SpeakersType', $speaker);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('speakers_edit', array('id' => $speaker->getId()));
        }

        return $this->render('speakers/edit.html.twig', array(
            'speaker' => $speaker,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a speaker entity.
     *
     * @Route("/{id}", name="speakers_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Speakers $speaker)
    {
        $form = $this->createDeleteForm($speaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($speaker);
            $em->flush();
        }

        return $this->redirectToRoute('speakers_index');
    }

    /**
     * Creates a form to delete a speaker entity.
     *
     * @param Speakers $speaker The speaker entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Speakers $speaker)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('speakers_delete', array('id' => $speaker->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
