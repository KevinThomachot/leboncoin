<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends EasyAdminController
{
   public function persistAnnoncesEntity(Annonces $entity){
       $entity->setCreatedOn(new \DateTime());
       $entity->setAuthor($this->getUser());

       parent::persistEntity($entity);

   } 
   public function persistUserEntity(User $entity, UserPasswordEncoderInterface $encoder)
   {

   }
}
