<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    #[Route('/MyCreatedProducts', name: 'user_created_products')]
    public function index(ProduitRepository $repo): Response
    {
        $Products=$repo->getUserProducts($this->getUser());
        return $this->render('products/userProducts.html.twig', [
            'products' => $Products,
        ]);
    }
}
