<?php

namespace App\Controller;

use App\Entity\Annonces;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminController extends EasyAdminController
{
   public function persistAnnoncesEntity(Annonces $entity){
       $entity->setCreatedOn(new \DateTime());
       $entity->setAuthor($this->getUser());

       parent::persistEntity($entity);

   } 
}
