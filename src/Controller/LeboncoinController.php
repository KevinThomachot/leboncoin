<?php

namespace App\Controller;

use App\Entity\Annonces;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LeboncoinController extends AbstractController
{
    /**
     * @Route("/index", name="leboncoin_index")
     */
    public function index()
    {
        $annonceRepository = $this->getDoctrine()->getRepository(Annonces::class);
        $annonces = $annonceRepository->findAll();

        return $this->render('leboncoin/index.html.twig', ['annonces' => $annonces]);
    }

    /**
     * @Route("/add", name="leboncoin_add")
     */
    public function add(Request $request)
    {
        $annonces = new Annonces();
        $annonces->setCreatedOn(new \DateTime);

        $form = $this->createFormBuilder($annonces)
        ->add('title', TextType::class)
        ->add('content', TextareaType::class)
        ->add('price', MoneyType::class)
        ->add('submit', SubmitType::class, ['label' => 'Envoyer l\'annonce'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager(); 
            $entityManager->persist($annonces); 
            $entityManager->flush();

            return $this->redirectToRoute('leboncoin_index');
        }
           return $this->render('leboncoin/add.html.twig', ['addForm' => $form->createView()]);
    }
    /**
     * @Route("/annonce/{id}", name="leboncoin_annonce")
     */
    public function annonce($id)
    {
        $annonceRepository = $this->getDoctrine()->getRepository(Annonces::class);
        $annonces = $annonceRepository->find($id);

        return $this->render('leboncoin/annonce.html.twig', ['annonce' => $annonces]);
    }
    /**
     * @Route("/delete/{id}", name="leboncoin_delete")
     */
    public function delete($id)
    {
        $annonceRepository = $this->getDoctrine()->getRepository(Annonces::class);
        $annonce = $annonceRepository->find($id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute('leboncoin_index');
    }
    /**
     * @Route("/edit/{id}", name="leboncoin_edit")
     */
    public function edit($id, Request $request)
    {
        $annonceRepository = $this->getDoctrine()->getRepository(Annonces::class);
        $annonce = $annonceRepository->find($id);

        $form = $this->createFormBuilder($annonce)
        ->add('title', TextType::class)
        ->add('content', TextareaType::class)
        ->add('price', MoneyType::class)
        ->add('submit', SubmitType::class, ['label' => 'Editer l\'annonce'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        
        return $this->redirectToRoute('leboncoin_annonce', ['id' => $annonce->getId()]);

        }
        
        return $this->render('leboncoin/edit.html.twig', ['editForm' => $form->createView()]);
    }
}