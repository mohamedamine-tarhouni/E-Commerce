<?php

namespace App\Controller;

use App\Form\SearchType;
use Doctrine\ORM\Mapping\Id;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    #[Route('/MyCreatedProducts', name: 'user_created_products')]
    public function index(ProduitRepository $repo,Request $request): Response
    {
        
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())    // si on a fait une recherche
        {
            $data = $form->get('Search')->getData();
            $Products = $repo->getProductsByName($data);
        }else{
            $Products=$repo->getUserProducts($this->getUser());
        }
        return $this->render('products/userProducts.html.twig', [
            'products' => $Products,
            'formRecherche' => $form->createView()
        ]);
    }
}
