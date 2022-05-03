<?php

namespace App\Controller;

use App\Form\UserformType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/Register', name: 'create_user')]
    public function form(): Response
    {
        $form = $this->createForm(UserformType::class);
        return $this->render('user/form.html.twig',[
            'userForm' => $form->createView(),
        ]);
    }
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
