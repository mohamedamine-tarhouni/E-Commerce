<?php

namespace App\Controller;

// use App\DataFixtures\Produit;
use App\Entity\Produit;
use App\Form\ProductType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function show_prod(Produit $product)
    {
        return $this->render('commerce/detailproduct.html.twig', [
            'product' => $product,
        ]);
    }
    #[Route('/createprod', name: 'create_prod')]
    public function create_prod(Request $request,EntityManagerInterface $manager)
    {
        // $session=$request->getSession();
        // $user = $session->get()
        $Produit=new Produit;
        $form = $this->createForm(ProductType::class,$Produit);
        dump($request);
        $form->handleRequest($request);
        $Produit->setUser($this->getUser());
        dump($Produit);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($Produit);
            $manager->flush();
            return $this->redirectToRoute('product',[
                'id' => $Produit->getId()
            ]);
        }
        return $this->render('forms/createprod.html.twig',[
            'formProduit' => $form->createView()
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
