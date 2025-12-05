<?php

namespace App\DataFixtures;

use App\Domain\Entity\Store;
use App\Domain\Entity\Product;
use App\Domain\Entity\User;
use App\Domain\Repository\StoreRepository;
use App\Domain\Repository\ProductRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly StoreRepository $storeRepository,
        private readonly ProductRepository $productRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $stores = [];

        // Создаём 5 складов
        for ($i = 1; $i <= 5; $i++) {
            $store = new Store();
            $store->setCode('store_' . $i);

            $this->storeRepository->save($store, false);
            $stores[] = $store;
        }

        // Создаём 30 продуктов
        for ($i = 1; $i <= 30; $i++) {
            $product = new Product();
            $product->setCode('product_' . $i);

            // Случайный склад
            $randomStore = $stores[array_rand($stores)];
            $product->setStore($randomStore);

            $this->productRepository->save($product, false);
        }

        // Создаём пользователей
        $usersData = [
            ['email' => 'admin@example.com', 'password' => 'admin123', 'roles' => ['ROLE_ADMIN']],
            ['email' => 'store_manager@example.com', 'password' => 'store123', 'roles' => ['ROLE_STORE_MANAGER']],
            ['email' => 'product_manager@example.com', 'password' => 'product123', 'roles' => ['ROLE_PRODUCT_MANAGER']],
            ['email' => 'user@example.com', 'password' => 'user123', 'roles' => ['ROLE_USER']],
        ];

        foreach ($usersData as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        // Один общий flush
        $manager->flush();
    }
}
