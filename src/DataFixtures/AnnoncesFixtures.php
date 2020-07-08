<?php

namespace App\DataFixtures;

use App\Entity\Annonces;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AnnoncesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i <= 20; $i++){
            $annonces = new Annonces();
            $annonces->setTitle('Test annonce #'.$i);
            $annonces->setContent(' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer tincidunt nibh sed nisl dictum elementum. Nam eu tellus in nisl pharetra euismod non sed sapien. Proin aliquet justo at quam posuere, quis pulvinar nisl rutrum. Sed sed felis quis nunc dictum pretium. Morbi nisi nisi, vulputate vel fringilla at, finibus nec odio. Integer mollis, nulla vitae mollis malesuada, tortor arcu dignissim erat, finibus consequat dolor odio ut leo. Duis ultrices scelerisque scelerisque. Proin blandit nunc luctus, malesuada lorem nec, ullamcorper nunc.
    
            Mauris non turpis sed dui luctus bibendum et id sapien. Etiam est nulla, laoreet nec lobortis a, scelerisque a lorem. Aliquam elit risus, pharetra vitae scelerisque eu, suscipit vitae quam. Praesent nisi risus, dapibus nec molestie vel, viverra cursus lectus. Suspendisse euismod varius nisi vel suscipit. Praesent quam libero, ultricies eu egestas id, venenatis sit amet lorem. Nulla in hendrerit mi, id aliquet arcu. ');
            $annonces->setPrice(random_int(10000, 1000000));
            $annonces->setCreatedOn(new \DateTime());
            $annonces->setPhotos('default');
            //$this->getReference permet de récupérer par son nom une reference à une entité créée dans une autre fixture
            $userNumber = random_int(1, 25);
            $annonces->setAuthor($this->getReference('fake-user'.$userNumber));

            //selection d'une catégorie aléatoire
            $categories = ['voiture', 'electromenager', 'vetement'];
            $categoryNumber = random_int(0, count($categories)-1);
            $annonces->setCategory($this->getReference($categories[$categoryNumber]));
            
            $manager->persist($annonces);
        }
        $manager->flush($annonces);
    }

    //pour indiquer un besoin de charger d'autres fixtures avant celles ci
    //on indique dans une fonction nommée getDependencies un tableau de fixtures dont celles ci sont dépendantes
    public function getDependencies(){
        return [CategoryFixtures::class, UserFixtures::class];
    }
}
