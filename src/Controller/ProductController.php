<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\ProductAddon;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ProductController extends AbstractController
{
    //рут на получение списка товаров и категорий
    #[Route('/products', name: 'app_product', methods: ['GET'])] 
    public function showProducts(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();
        $categories = $entityManager->getRepository(ProductCategory::class)->findAll();

        return $this->render('products.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    #[Route('/api/cart', name: 'api_cart_get', methods: ['GET'])]
    public function getCart(Request $request): JsonResponse //Request используется для доступа к текущему запросу
    {
        $cart = $request->getSession()->get('cart', []); // получаем корзину из сессии, есои корзина пуста, возвращаем пустой массив
        return new JsonResponse(['cart' => $cart]);
    }

    #[Route('/api/cart', name: 'api_cart_update', methods: ['PUT'])]
    public function updateCart(Request $request): JsonResponse
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        $data = json_decode($request->getContent(), true); // Принимает закодированную в JSON строку и преобразует ее в переменную PHP

        foreach ($data['items'] as $item) {
            $product = $entityManager->getRepository(Product::class)->find($item['product_id']);
            if ($product) {
                $cartItem = [
                    'product' => [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'price' => $product->getPrice(),
                    ],
                    'quantity' => $item['quantity'],
                    'addons' => []
                ];

                foreach ($item['addons'] as $addonId) {
                    $addon = $entityManager->getRepository(ProductAddon::class)->find($addonId);
                    if ($addon) {
                        $cartItem['addons'][] = [
                            'id' => $addon->getId(),
                            'name' => $addon->getName(),
                            'price' => $addon->getPrice(),
                        ];
                    }
                }

                $existingItemIndex = array_search($product->getId(), array_column($cart, 'product_id'));
                if ($existingItemIndex !== false) {
                    $cart[$existingItemIndex] = $cartItem;
                } else {
                    $cart[] = $cartItem;
                }
            }
        }
        $session->set('cart', $cart);

        return new JsonResponse($cart);
    }

    #[Route('/api/checkout', name: 'api_checkout', methods: ['POST'])]
    public function checkout(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cart = $request->getSession()->get('cart', []);

        if (empty($cart)) {
            return new JsonResponse(['message' => 'Корзина пуста'], Response::HTTP_BAD_REQUEST);
        }
        
        $email = (new Email())
            ->from('tazmin.vadim31@gmail.com')
            ->to('tazmin.vadim@mail.ru')
            ->subject('Новый заказ')
            ->html($this->renderView('order.html.twig', [
                'cart' => $cart,
            ]));

        $mailer->send($email);

        return new JsonResponse(['message' => 'Заявка отправлена']);
    }
}
