<?php

namespace UserListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use UserListBundle\Entity\Address;
use UserListBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use UserListBundle\Form\UserType;
use UserListBundle\Form\AddressType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AddressController extends Controller
{

    /**
     * @Route("/newAddress" , name="newaddress" , methods="GET")
     * @Route("/newAddress" , name="createaddress" , methods="POST")
     * @Template("@UserList/Address/newAddressForm.html.twig")
     */

    public function newAddressAction(SessionInterface $session, Request $request)
    {
        if ($request->isMethod('GET')) {
            $address = new Address();
            $form = $this->createForm(AddressType::class, $address, [
                'action' => $this->generateUrl('createaddress')
            ]);
            return ['form' => $form->createView()];
        }

        $createAddress = new Address();
        $form = $this->createForm(AddressType::class, $createAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $createAddress = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($createAddress);
            $em->flush();

//            $url = $this->generateUrl('showallusers');
//            return $this->redirect($url);
            $redirectToShowOne = $session->get('showOneUser_id');
            $session->clear('showOneUser_id');
            return $this->redirect('/'.$redirectToShowOne);
        }
    }

    /**
     * @Route("/{id}/modifyAddress" , name="modifyaddress")
     * @Template("@UserList/Address/newAddressForm.html.twig")
     */

    public function modifyAddressAction(SessionInterface $session, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserListBundle:Address');
        $address = $repository->find($id);

        if (!$address) {
            return new Response('Adres o podanym ID nie istnieje');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();


            $redirectToShowOne = $session->get('showOneUser_id');
            $session->clear('showOneUser_id');
            return $this->redirect('/'.$redirectToShowOne);
        }
        return['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/deleteAdress" , name="deleteaddress")
     */

    public function deleteAddressAction(SessionInterface $session, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserListBundle:Address');
        $address = $repository->find($id);

        if (!$address) {
            return new Response('Adres o podanym ID nie istnieje');
        }
        $em->remove($address);
        $em->flush();

        $redirectToShowOne = $session->get('showOneUser_id');
        $session->clear('showOneUser_id');
        return $this->redirect('/'.$redirectToShowOne);
    }
}
