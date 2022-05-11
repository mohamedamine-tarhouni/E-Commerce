<?php

namespace App\Controller;

// use App\DataFixtures\Produit;
use DateTime;
use App\Entity\Produit;
use App\Form\SearchType;
use App\Form\ProductType;
use App\Entity\Commentaire;
use App\Form\AjoutPanierType;
use App\Form\CommentaireType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommerceController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(ProduitRepository $repo,Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())    // si on a fait une recherche
        {
            $data = $form->get('Search')->getData();
            $Products = $repo->getProductsByName($data);
        }
        else    // sinon, pas de recherche : on récupère tout
        {
            $Products = $repo->getProducts();
        }
        $Product = $repo->findAll();
        return $this->render('commerce/products.html.twig', [
            'products' => $Products,
            'formRecherche' => $form->createView()
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
    #[Route('/editprod/{id}', name: 'edit_prod')]
    public function create_prod(
        Request $request,
        EntityManagerInterface $manager,
        Produit $Produit=null
    ) {
        if(!$Produit){
            $Produit = new Produit();
            $Produit->setUser($this->getUser());
        }
        $form = $this->createForm(ProductType::class, $Produit);
        $form->handleRequest($request);
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
            'editMode'=> $Produit->getId() !== null
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
