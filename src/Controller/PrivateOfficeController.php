<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PrivateOfficeController extends AbstractController
{
    /**
     * @Route("/private_office", name="private_office")
     */
    public function index(Request $request): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('home');
        }

        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user, [
            'action' => $this->generateUrl('user_edit'),
            'method' => 'PUT',
        ]);
        $form->handleRequest($request);

        return $this->render('private_office/index.html.twig', [
            'userForm' => $form->createView(),
            'controller_name' => 'PrivateOfficeController',
        ]);
    }

    /**
     * @Route("/private_office/edit", name="user_edit")
     */
    public function userEdit(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user, [
            'action' => $this->generateUrl('user_edit'),
            'method' => 'PUT',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            var_dump($form->isValid());
            if ($user->getEmail() != $form->get('email')->getData())
                $user->setEmail($form->get('email')->getData());

            if ($user->getFullName() != $form->get('full_name')->getData())
                $user->setFullName($form->get('full_name')->getData());

            if ($user->getPhone() != $form->get('phone')->getData())
                $user->setPhone($form->get('phone')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('private_office');
        }

        return $this->render('private_office/index.html.twig', [
            'userForm' => $form->createView(),
            'controller_name' => 'PrivateOfficeController',
        ]);
    }
}
