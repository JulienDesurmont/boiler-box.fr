<?php
// Lci/BoilerBox/Form/Type/ChangePasswordFormType.php

namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ChangePasswordFormType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'attr'  => array (
                'placeholder'   => 'mot de passe',
                'class'         => 'input_txt_large'
             ),
            'mapped' => false,
            'constraints' => new UserPassword(),
        ));
        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array(
                'label' => 'form.new_password',
                'attr'  => array (
                    'placeholder'   => 'nouveau mot de passe',
                    'class'         => 'input_txt_large'
                 )
            ),
            'second_options' => array(
                'label' => 'form.new_password_confirmation',
                'attr'  => array (
                    'placeholder'   => 'vérification du mot de passe',
                    'class'         => 'input_txt_large'
                 )
            ),
            'invalid_message' => 'fos_user.password.mismatch',
        ));
    }


    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\User'
        ));
    }

    public function getName() {
        return 'lci_user_change_password';
    }
}
