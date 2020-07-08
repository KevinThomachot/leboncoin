<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoryRepository;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class LeboncoinController extends AbstractController
{
    /**
     * @Route("/", name="leboncoin_index")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $annonceRepository = $this->getDoctrine()->getRepository(Annonces::class);
        $annonces = $annonceRepository->findBy([], ['created_on' => 'DESC']);

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->findAll();

        $annonces = $paginator->paginate($annonces, $request->query->getInt('page', 1), 3);

        return $this->render('leboncoin/index.html.twig',  ['annonces' => $annonces,
        'categories' => $category
        ]);

    }

    /**
     * @Route("/add", name="leboncoin_add")
     */
    public function add(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $annonces = new Annonces();
        $annonces->setCreatedOn(new \DateTime);
        $user = $this->getUser();
        $annonces->setAuthor($user);

        $form = $this->createFormBuilder($annonces)
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'name'])
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('price', MoneyType::class)
            ->add('photosFile', VichImageType::class, ['required' => false])
            ->add ('captcha', CaptchaType:: class)
            ->add('submit', SubmitType::class, ['label' => 'Valider l\'annonce'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonces);
            $entityManager->flush();

            $this->addFlash('success', 'Votre annonce a bien été créer !');

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

        $this->addFlash('success', 'Votre annonce a bie été supprimée !');

        return $this->redirectToRoute('leboncoin_index');
    }
    /**
     * @Route("/edit/{id}", name="leboncoin_edit")
     */
    public function edit($id, Request $request)
    {

        $annonceRepository = $this->getDoctrine()->getRepository(Annonces::class);
        $annonce = $annonceRepository->find($id);
        $annonce->setCreatedOn(new \DateTime);

        $form = $this->createFormBuilder($annonce)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('price', MoneyType::class)
            ->add('photosFile', VichImageType::class, ['required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Editer l\'annonce'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Votre annonce a bien été modifiée !');

            return $this->redirectToRoute('leboncoin_annonce', ['id' => $annonce->getId()]);
        }

        return $this->render('leboncoin/edit.html.twig', ['editForm' => $form->createView()]);
    }
    /**
     * @Route("/compte/{id}", name="leboncoin_compte")
     */
    public function editCompte($id, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $user = $this->getUser();

        $annonceRepository = $this->getDoctrine()->getRepository(Annonces::class);
        $annonces = $annonceRepository->findBy(['author' => $user], ['created_on' => 'DESC']);

        $form = $this->createFormBuilder($user)
            ->add('password', PasswordType::class, ['mapped' => false])
            ->add('submit', SubmitType::class, ['label' => 'Editer votre mot de passe'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));

            $entityManager = $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été changé !');

            return $this->redirectToRoute('leboncoin_compte', ['id' => $user->getId()]);
        }

        return $this->render('leboncoin/compte.html.twig', ['compteForm' => $form->createView(), 'annonces' => $annonces]);
    }

    /**
     * @Route("/category/{name}", name="leboncoin_category")
     */
    public function category($name, CategoryRepository $categoryRepository, AnnoncesRepository $annonceRepository )
    {
        $category = $categoryRepository->findOneBy(['name' => $name]);

        $annonces = $annonceRepository->findBy(['category' => $category]);

        return $this->render('leboncoin/category.html.twig', ['annonces' => $annonces]);
    }
}
