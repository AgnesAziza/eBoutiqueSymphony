<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add(ProductRepository $productRepository, SessionInterface $session, int $id): Response
    {
        $product = $productRepository->find($id);

        $cart = $session->get('cart', []);
        $cart[$id] = ($cart[$id] ?? 0) + 1;

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove(SessionInterface $session, int $id): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    /**
 * @Route("/cart/adjust/{id}", name="cart_adjust")
 */
    public function adjust(Request $request, SessionInterface $session, int $id): Response
    {
        $newQuantity = $request->request->getInt('quantity');

        $cart = $session->get('cart', []);
        if ($newQuantity > 0) {
            $cart[$id] = $newQuantity;
        } else {
            unset($cart[$id]); 
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }



    #[Route('/cart', name: 'app_cart')]
    public function getFullCart(SessionInterface $session, ProductRepository $productRepository): Response
    {
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
    
        return $this->render('cart/index.html.twig', [
            'cart' => $cartWithData,
            'total' => $total
        ]);
    }

}
