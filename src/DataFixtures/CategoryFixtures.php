<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture{

    public function load (ObjectManager $manager){
        $categorieVoiture = new Category();
        $categorieVoiture->setName("voiture");
        //on ajoute une ref a cette categorie pour utilisation future dans une autre fixture
        $this->addReference($categorieVoiture->getName(), $categorieVoiture);

        $categorieElectromenager = new Category();
        $categorieElectromenager->setName("electromenager");
        $this->addReference($categorieElectromenager->getName(), $categorieElectromenager);
        
        
        $categorieVetement = new Category();
        $categorieVetement->setName("vetement");

        $this->addReference($categorieVetement->getName(), $categorieVetement);
        
        $manager->persist($categorieVoiture);
        $manager->persist($categorieVetement);
        $manager->persist($categorieElectromenager);

        $manager->flush();
    }
}

