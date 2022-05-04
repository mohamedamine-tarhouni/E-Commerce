<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommerceController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(CategorieRepository $repo): Response
    {
        $Categories = $repo->findAll();
        return $this->render('commerce/products.html.twig', [
            'categories' => $Categories,
        ]);
    }
    // #[Route('/product/{id}', name: 'product')]
    // public function show_prod(Categorie $categorie){
    //     return $this-> render('commerce/detailproduct.html.twig',[
    //         'categorie' => $categorie
    //     ]);
    // } 
    #[Route('/commerce', name: 'app_commerce')]
    public function index(): Response
    {
        return $this->render('commerce/index.html.twig', [
            'controller_name' => 'CommerceController',
        ]);
    }
}
