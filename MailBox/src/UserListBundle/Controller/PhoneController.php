<?php

namespace UserListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UserListBundle\Entity\User;
use UserListBundle\Form\PhoneType;
use UserListBundle\Entity\Phone;

class PhoneController extends Controller
{
    /**
     * @Route("/newPhone" , name="newphone" , methods="GET")
     * @Route("/newPhone" , name="createphone" , methods="POST")
     * @Template("@UserList/User/userForm.html.twig")
     */

    public function newPhoneAction(SessionInterface $session, Request $request)
    {
        if ($request->isMethod('GET')) {
            $phone = new Phone();
            $form = $this->createForm(PhoneType::class, $phone, [
                'action' => $this->generateUrl('createphone')
            ]);
            return ['form' => $form->createView()];
        }

        $createPhone = new Phone();
        $form = $this->createForm(PhoneType::class, $createPhone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $createPhone = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($createPhone);
            $em->flush();

            $redirectToShowOne = $session->get('showOneUser_id');
            $session->clear('showOneUser_id');
            return $this->redirect('/'.$redirectToShowOne);
        }
    }

    /**
     * @Route("/{id}/modifyPhone" , name="modifyphone")
     * @Template("@UserList/User/userForm.html.twig")
     */

    public function modifyPhoneAction(SessionInterface $session, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserListBundle:Phone');
        $phone = $repository->find($id);

        if (!$phone) {
            return new Response('Telefon o podanym ID nie istnieje');
        }

        $form = $this->createForm(PhoneType::class, $phone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $phone = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($phone);
            $em->flush();


            $redirectToShowOne = $session->get('showOneUser_id');
            $session->clear('showOneUser_id');
            return $this->redirect('/'.$redirectToShowOne);
        }
        return['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/deletePhone" , name="deleteaphone")
     */

    public function deletePhoneAction(SessionInterface $session, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserListBundle:Phone');
        $phone = $repository->find($id);

        if (!$phone) {
            return new Response('Numer o podanym ID nie istnieje');
        }
        $em->remove($phone);
        $em->flush();

        $redirectToShowOne = $session->get('showOneUser_id');
        $session->clear('showOneUser_id');
        return $this->redirect('/'.$redirectToShowOne);
    }
}
