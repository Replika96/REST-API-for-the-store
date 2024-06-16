<?php

namespace App\DataFixtures;

use App\Entity\ProductCategory;
use App\Entity\Product;
use App\Entity\ProductAddon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            'Сочные бургеры' => ['Чизбургер', 'Гамбургер', 'Гранд', 'Фиш бургер'],
            'В лепешке' => ['Шаурма', 'Цезарь Ролл'],
            'Напитки' => ['Компот', 'Морс', 'Манговый сок']
        ];

        $addonsData = [
            'Горчица' => 29,
            'Огурцы' => 20,
            'Халапеньо' => 39,
            'Грибы' => 25
        ];

        $addons = [];
        foreach ($addonsData as $name => $price) {
            $addon = new ProductAddon();
            $addon->setName($name);
            $addon->setPrice($price);
            $manager->persist($addon);
            $addons[] = $addon;
        }

        foreach ($categoriesData as $categoryName => $products) {
            $category = new ProductCategory();
            $category->setName($categoryName);
            $manager->persist($category);

            foreach ($products as $productName) {
                $product = new Product();
                $product->setName($productName);
                $product->setPrice(rand(100, 200));
                $product->setDescription('Описание ' . $productName);
                $product->setCategory($category);
                if ($categoryName !== 'Напитки') {
                    foreach ($addons as $addon) {
                        $product->addProductAddon($addon);
                    }
                }
                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
