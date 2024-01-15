<?php

namespace App\DataFixtures;

use App\Entity\Ingredients;
use App\Entity\Recettes;
use Doctrine\Bundle\FixturesBundle\Fixture;
// use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this-> faker = FakerFactory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Création de fausses données pour Ingredients
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredients();
        $ingredient->setNom($this->faker->word())
                ->setPrix(mt_rand(1,200));
        $manager->persist($ingredient);
        // $product = new Product();
        // $manager->persist($product);
        }

    // Création de fausses données pour Ingredients
        for ($i=0; $i < 20; $i++) { 
            $recette = new Recettes();
        $recette->setNom($this->faker->sentence())
                ->setTemps(mt_rand(5, 120))
                ->setNombrePersonnes(mt_rand(1, 10))
                ->setDifficulte(mt_rand(1, 5))
                ->setEtapes($this->faker->paragraphs(3, true))
                ->setPrix(mt_rand(1, 500))
                ->setFavori($this->faker->boolean());
                // ->setCreatedAt(new \DateTimeImmutable())
                // ->setUpdatedAt(new \DateTimeImmutable());
                
                $ingredients = $manager->getRepository(Ingredients::class)->findAll();

                // Assurez-vous que $ingredients n'est pas vide avant d'appeler randomElements
                if (!empty($ingredients)) {
                    $randomIngredients = $this->faker->randomElements($ingredients, mt_rand(2, 5));
                    foreach ($randomIngredients as $ingredient) {
                        $recette->addListIngredient($ingredient);
                    }
                }

                $manager->persist($recette);
            }
    
            $manager->flush();
        }
    }


