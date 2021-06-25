<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i<11; $i++):
            $article= new Article();// ici on instancie, un nouvel objet hérité de la class App\entity\Article à chaque tour de boucle, pour autant darticles instanciés, il y aura un insert supplémentaire en BDD
            $article->setNom("Article N°" .$i)
                ->setPrix(rand(100,400))
                ->setDateCrea(new \DateTime())
                ->setRef("ref".$i)
                ->setPhoto("https://picsum.photos/600/" .$i);
            //ici on "habille nos objets nus instanciés précedemment" avec les setter de nos différentes propriétés héritées de notre objet Article entité
            $manager->persist($article);//persit est une méthode issue de la class ObjectManager qui permet de garder en mémoire les objets articles créés précédemment et de préparer et binder les requêtes insertions (valeurs à insérer)
        endfor;


        $manager->flush(); // flush est une méthode de ObjectManager qui permet dexécuter les requêtes préparées lors du persist() et permet ainsi les inserts en BDD
        //une fois réalisé, il faut changer les Fixtures en BDD grâce à DOCTRINE avec la commande suivante: php bin/console doctrine:fixtures:load
    }
}
