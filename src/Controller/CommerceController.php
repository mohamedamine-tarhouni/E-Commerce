<?php

namespace App\Controller;

// use App\DataFixtures\Produit;
use DateTime;
use App\Entity\Produit;
use App\Form\ProductType;
use App\Entity\Commentaire;
use App\Form\AjoutPanierType;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
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
    //on est dans un produit avec l'id 7
    //findallcomments(7)
    #[Route('/product/{id}', name: 'product')]
    public function show_prod(
        Produit $product,
        Request $request,
        EntityManagerInterface $manager,
        CommentaireRepository $repo
    ) {
        $Commentaires = $repo->getCommentaires($product->getId());
        dump($Commentaires);
        dump($product->getId());
        $Commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $Commentaire);
        $form->handleRequest($request);
        $Commentaire->setUser($this->getUser());
        $Commentaire->setCreatedAt(new DateTime());
        $Commentaire->setproduit($product);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($Commentaire);
            $manager->flush();
            $this->addFlash('success', 'Commentaire correctement enregistré !');
            return $this->redirectToRoute('product', [
                'id' => $product->getId(),
            ]);
        }
        return $this->render('commerce/detailproduct.html.twig', [
            'product' => $product,
            'formCommentaire' => $form->createView(),
            'Commentaires' => $Commentaires
        ]);
    }
    // #[Route('/product/{id}', name: 'product')]
    // public function mon_panier(
    //     Request $request,
    //     Produit $product,
    //     EntityManagerInterface $manager
    // ) {
    //     $product = new Produit();
    //     $form = $this->createForm(AjoutPanierType::class, $product);
    //     $form->handleRequest($request);
    //     dump($request);
    //     $product->setUser($this->getUser());
    //     dump($product);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $manager->persist($product);
    //         $manager->flush();
    //         $this->addFlash('success', 'Ajouté au panier');
    //         return $this->redirectToRoute('product', [
    //             'id' => $product->getId(),
    //         ]);
    //     }
    //     return $this->render('commerce/panier.html.twig', [
    //         // 'formProduit' => $form->createView(),
    //         'product' => $product,
    //         'formPanier' => $form->createView(),
    //     ]);
    // }
    // #[Route('/monpanier', name: 'mon_panier')]
    // public function displayCart(
    //     Request $request,
    //     Produit $product,
    //     EntityManagerInterface $manager
    // ) {
    //     return $this->render('commerce/panier.html.twig', [
    //         // 'formProduit' => $form->createView(),

    //     ]);
    // }

    #[Route('/createprod', name: 'create_prod')]
    public function create_prod(
        Request $request,
        EntityManagerInterface $manager
    ) {
        // $session=$request->getSession();
        // $user = $session->get()
        $Produit = new Produit();
        $form = $this->createForm(ProductType::class, $Produit);
        $form->handleRequest($request);
        dump($request);
        $Produit->setUser($this->getUser());
        dump($Produit);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($Produit);
            $manager->flush();
            $this->addFlash('success', 'Produit correctement enregistré !');
            return $this->redirectToRoute('product', [
                'id' => $Produit->getId(),
            ]);
        }
        return $this->render('forms/createprod.html.twig', [
            'formProduit' => $form->createView(),
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
