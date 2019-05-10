<?php

namespace UserListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

    public function newAddressAction(Request $request)
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

            $url = $this->generateUrl('showallusers');
            return $this->redirect($url);
        }
    }
}
