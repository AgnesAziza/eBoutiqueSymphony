<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;


class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(SessionInterface $session, ProductRepository $productRepository): Response
    {
        // Récupère l'utilisateur actuellement connecté
        /** @var User $user */
        $user = $this->getUser();
        

        // Si l'utilisateur n'est pas connecté, le redirige vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupère les adresses de l'utilisateur
        $addresses = $user->getAddresses();

        // Si l'utilisateur n'a pas d'adresse, le redirige vers la page d'ajout d'adresse
        if (count($addresses) == 0) {
            return $this->redirectToRoute('app_address_add');
        }

            // Récupère le panier de l'utilisateur
    $cart = $session->get('cart', []);
    $cartWithData = [];

    foreach ($cart as $id => $quantity) {
        $cartWithData[] = [
            'product' => $productRepository->find($id),
            'quantity' => $quantity
        ];
    }

    $total = 0;

    foreach ($cartWithData as $item) {
        $totalItem = $item['product']->getPriceHT() * $item['quantity'];
        $total += $totalItem;
    }

        // Crée une nouvelle commande
        $order = new Order();

        // TODO: Ajoutez le code pour gérer le processus de paiement ici

        return $this->render('checkout/index.html.twig', [
            'addresses' => $addresses,
            'order' => $order,
            'cart' => $cartWithData,
        'total' => $total
        ]);
    }
}



