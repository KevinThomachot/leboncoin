<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('plainPassword', RepeatedType::class, [
                //RepeatedType permet de définir un deuxiémé champs de vérification (mot de passe par exemple)
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => true,
                'invalid_message' => 'Les deux mots de passe ne correspondent pas !',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                //pour définit de soptions pour chacun des deux champs on peut utiliser 
                //first/second_options
                'first_options' => ['label' => 'mot de passe'],
                'second_options' => ['label' => 'Répeter mot de passe']
            ])
            ->add('avatarFile', VichImageType::class, [
                //mapped => false permet d'empêche le traitement automatique de la donnée de formulaire pour la stocker dans l'objet
                //cette option est neccésaire quand un traitement est demandé sur mla donnée avant de la stocker dans l'objet
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
