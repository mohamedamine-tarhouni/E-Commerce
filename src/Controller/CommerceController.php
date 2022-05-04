<?php

namespace App\Controller;

// use App\DataFixtures\Produit;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommerceController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(ProduitRepository $repo): Response
    {
        $Product = $repo->findAll();
        return $this->render('commerce/products.html.twig', [
            'products' => $Product,
        ]);
    }
    #[Route('/product/{id}', name: 'product')]
    public function show_prod(Produit $product){
        return $this-> render('commerce/detailproduct.html.twig',[
            'product' => $product
        ]);
    } 
    #[Route('/commerce', name: 'app_commerce')]
    public function index(): Response
    {
        return $this->render('commerce/index.html.twig', [
            'controller_name' => 'CommerceController',
        ]);
    }
}
