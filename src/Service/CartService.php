<?php

namespace App\Service;

use App\Cart\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }

        $cart[$id]++;

        $this->saveCart($cart);
    }

    public function remove(int $id)
    {
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->saveCart($cart);
    }

    /**
     * Retire une quantité d'un produit dans le panier
     */
    public function decrement(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        if ($cart[$id] === 1) {
            $this->remove($id);
        } else {
            $cart[$id]--;
        }

        $this->saveCart($cart);
    }

    /**
     * getTotal : Renvoie le total du panier
     *
     * @return int
     */
    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += ($product->getPrice() * $qty);
        }

        return $total;
    }

    /**
     * getDetailedCartItems : Renvoie le détail des items du panier
     *
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];

        foreach ($this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $detailedCart[] = new CartItem($product, $qty);
        }

        return $detailedCart;
    }

    /**
     * Récupère le panier en session
     * @return array 
     */
    protected function getCart(): ?array
    {
        return $this->session->get('cart', []);
    }

    /**
     * Enregistre le panier en session
     * @param array $cart 
     * @return void 
     */
    protected function saveCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }

    /**
     * Vide le panier après une commande
     *
     * @return void
     */
    public function empty()
    {
        $this->saveCart([]);
    }
}
