<?php

namespace UserListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UserListBundle\Entity\User;
use UserListBundle\Entity\Email;
use UserListBundle\Form\EmailType;

class EmailController extends Controller
{
    /**
     * @Route("/newEmail" , name="newemail" , methods="GET")
     * @Route("/newEmail" , name="createemail" , methods="POST")
     * @Template("@UserList/User/userForm.html.twig")
     */

    public function newEmailAction(SessionInterface $session, Request $request)
    {
        if ($request->isMethod('GET')) {
            $email = new Email();
            $form = $this->createForm(EmailType::class, $email, [
                'action' => $this->generateUrl('createemail')
            ]);
            return ['form' => $form->createView()];
        }

        $createEmail = new Email();
        $form = $this->createForm(EmailType::class, $createEmail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $createEmail = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($createEmail);
            $em->flush();

            $redirectToShowOne = $session->get('showOneUser_id');
            $session->clear('showOneUser_id');
            return $this->redirect('/'.$redirectToShowOne);
        }
    }

    /**
     * @Route("/{id}/modifyEmail" , name="modifyemail")
     * @Template("@UserList/User/userForm.html.twig")
     */

    public function modifyEmailAction(SessionInterface $session, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserListBundle:Email');
        $email = $repository->find($id);

        if (!$email) {
            return new Response('Email o podanym ID nie istnieje');
        }

        $form = $this->createForm(EmailType::class, $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($email);
            $em->flush();


            $redirectToShowOne = $session->get('showOneUser_id');
            $session->clear('showOneUser_id');
            return $this->redirect('/'.$redirectToShowOne);
        }
        return['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/deleteEmail" , name="deleteemail")
     */

    public function deleteEmailAction(SessionInterface $session, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserListBundle:Email');
        $email = $repository->find($id);

        if (!$email) {
            return new Response('Email o podanym ID nie istnieje');
        }
        $em->remove($email);
        $em->flush();

        $redirectToShowOne = $session->get('showOneUser_id');
        $session->clear('showOneUser_id');
        return $this->redirect('/'.$redirectToShowOne);
    }
}
