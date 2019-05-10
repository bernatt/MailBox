<?php

namespace UserListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UserListBundle\Entity\User;
use UserListBundle\Entity\Address;
use UserListBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserFunctionController extends Controller
{
    /**
     * @Route("/new", name="newuser", methods="GET")
     * @Route("/new", name="createuser", methods="POST")
     * @Template("@UserList/User/userForm.html.twig")
     */

    public function newUserAction(Request $request)
    {
        if ($request->isMethod('GET')) {
            $user = new User();
            $form = $this->createForm(UserType::class, $user, [
                'action' => $this->generateUrl('createuser')
            ]);
            return ['form' => $form->createView()];
        }

        $createUser = new User();
        $form = $this->createForm(UserType::class, $createUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $createUser = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($createUser);
            $em->flush();

            $url = $this->generateUrl('showallusers');
            return $this->redirect($url);
        }

    }


    /**
     * @Route("/{id}/modify" , name="modifyuser")
     * @Template("@UserList/User/userForm.html.twig")
     */

    public function modifyUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserListBundle:User');
        $user = $repository->find($id);

        if (!$user) {
            return new Response('Użytkownik o podanym ID nie istnieje');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $url = $this->generateUrl('showallusers');
            return $this->redirect($url);
        }
        return['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/delete", name="deleteuser")
     */

    public function deleteBookAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserListBundle:User');
        $user = $repository->find($id);

        if (!$user) {
            return new Response('Użytkownik o podanym ID nie istnieje');
        }
        $em->remove($user);
        $em->flush();

        $url = $this->generateUrl('showallusers');
        return $this->redirect($url);
    }

    /**
     * @Route("/{id}" , name="showone" , methods="GET")
     * @Template("@UserList/User/showOneUser.html.twig")
     */

    public function showOneUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserListBundle:User');
        $user = $repository->find($id);

        $repository2 = $em->getRepository('UserListBundle:Address');
        $address = $repository2->findByUser($id); //findByUser user z Encji adresu ( wczytuje wszystkie pozycje dla usera o danym id)
        $addressesCount = count($address);
        return[
            'user' => $user,
            'address' => $address,
            'count' => $addressesCount
        ];
    }

    /**
     * @Route("/", name="showallusers")
     * @Template("@UserList/User/showAllUsers.html.twig")
     */

    public function showAllUsersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserListBundle:User');
        $users = $repository->findAll();

        return[
            'users' => $users
        ];
    }
}
