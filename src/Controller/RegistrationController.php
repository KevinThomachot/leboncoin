<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="leboncoin_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
        
            //gestion du téléchargement d'image de profil
            //on récupére l'image dans notre formulaire
            $imageFile = $form->get('picture')->getData();
            //pour stocker sur notre serveur on doit d'abord lui donner un nom unique
            $uniqueName = md5(uniqid("", true)); //génére un id unique de 23 caractére hashé en md5 pour id de 32 char
            //pour définir le nom du fichier final on a également besoin de l'extension du fichier
            $fileName = $uniqueName . '.' . $imageFile->guessExtension(); //guessExtension renvoie l'extension supposée du fichier téléchargé
            //maintenant qu'on a un nom pour notre fichier on va tenter de l'enregistrer sur notre serveur
            //move permet de déplacer le fichier vers une destination de notre choix et de lui donner un nom
            $imageFile->move("uploads/userPictures/", $fileName);
            //on peut ensuite enregistrer le nom du fichier dans notre entité
            $user->setavatar($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('leboncoin_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
