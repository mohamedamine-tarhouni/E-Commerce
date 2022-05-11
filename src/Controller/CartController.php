<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $cart = $session->get('cart',[]);
        $cartWithData = [];
        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $produitRepository->find($id),
                'quantity' => $quantity
            ];
        }
        $total = 0;
        foreach ($cartWithData as $item) {
            $totalItem = $item['product']->getPrix() * $item['quantity'];
            $total += $totalItem;
        }
        // dd($cartWithData);
        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, SessionInterface $session)
    {
        //recup/creer une session grace a requeststack

        $cart = $session->get('cart', []);
        //recup attr de session cart s'i existe ou tableau vide
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute(('app_cart'));
        //je sauvegarde l'état de mon panier à l'attr de session 'cart'
        // dd($session->get('cart'));
        //dd()=dump and die : afficher les infos tuer l'exécution du code
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute(('app_cart'));
    }
    #[Route('/cart/removeone/{id}', name: 'cart_remove_one')]
    public function remove_one($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        if ($cart[$id] == 1) {
            unset($cart[$id]);
        } else {
            $cart[$id]--;
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute(('app_cart'));
    }
}