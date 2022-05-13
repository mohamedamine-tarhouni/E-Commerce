<?php

namespace App\Controller;

// use App\DataFixtures\Produit;
use DateTime;
use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Produit;
use App\Form\SearchType;
use App\Form\ContactType;
use App\Form\ProductType;
use App\Entity\Commentaire;
use App\Form\AjoutPanierType;
use App\Form\CommentaireType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\ContactNotification;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommerceController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(ProduitRepository $repo, Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // si on a fait une recherche
            $data = $form->get('Search')->getData();
            $Products = $repo->getProductsByName($data);
        }
        // sinon, pas de recherche : on récupère tout
        else {
            $Products = $repo->getProducts();
        }
        $Product = $repo->findAll();
        return $this->render('commerce/products.html.twig', [
            'products' => $Products,
            'formRecherche' => $form->createView(),
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
            'Commentaires' => $Commentaires,
        ]);
    }

    #[Route('/createprod', name: 'create_prod')]
    #[Route('/editprod/{id}', name: 'edit_prod')]
    // #[ParamConverter("id", class="Produit", options={"id": "id"})]
    public function create_prod(
        Request $request,
        EntityManagerInterface $manager,
        Produit $Produit = null,
        ProduitRepository $rs,
        $id=null
    ) {
        $iseditable=false;
        if (!$Produit) {
            if($id == null){
                $Produit = new Produit();
                $Produit->setUser($this->getUser());
                $testProduit[0]='0';
                $iseditable=true;
            }else{
                $testProduit[0]=0;
            }
            dump($id);
        } else {
            dump($id);
            $testProduit = $rs->geteditedproduct($id);
            $iseditable=true;
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
            'editMode' => $Produit != null and $Produit->getId() !== null ,
            'createMode'=>$testProduit[0]=='0',
            'editedProd' => $testProduit[0],
            'productexists' => $iseditable,
        ]);
    }

    #[Route('/contact', name: 'commerce_contact')]
    public function contact(Request $request, EntityManagerInterface $manager,ContactNotification
    $notification): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        $contact->setCreatedAt(new DateTime());
        if ($form->isSubmitted() && $form->isValid()) {
        $notification->notify($contact);
        $this->addFlash('success', 'Votre Email a bien été envoyé');
        $manager->persist($contact); // on prépare l'insertion
        $manager->flush(); // on execute l'insertion
        }
       
        return $this->render("forms/contact.html.twig", [
        'formContact' => $form->createView()
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
