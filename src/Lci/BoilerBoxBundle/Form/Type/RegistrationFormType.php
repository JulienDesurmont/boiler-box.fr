<?php
// Lci/BoilerBox/Form/Type/RegistrationFormType.php

namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder
			->add('roles', 'choice', array(
					'label'     => 'Rôles',
					'choices' => $this->fillRoles($options['em']),
					'expanded'  => false,
            		'multiple' => true,
					'attr' => array(
                		'placeholder'   => 'Rôle',
                		'class'         => 'select_role'
            		)
				))
                ->add('username', null, array(
                    'label'              => 'Utilisateur', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr'=> array (
                        'placeholder'   => 'Nom utilisateur',
                        'class'         => 'input_txt_large'
                    )
                ))
                ->add('email', 'email', array(
                    'label' => 'form.email', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr'=> array (
                        'placeholder'   => 'adresse mail',
                        'class'         => 'input_txt_large'
                    )
                ))
                ->add('plainPassword', 'repeated', array(
                    'type'              => 'password',
                    'options'           => array('translation_domain' => 'FOSUserBundle'),
                    'first_options'     => array(
                        'label' => 'form.password',
                        'attr'  => array (
                            'placeholder'   => 'mot de passe',
                            'class'         => 'input_txt_large'
                        )
                    ),
                    'second_options'    => array(
                        'label' => 'form.password_confirmation',
                        'attr'  => array (
                            'placeholder'   => 'vérification du mot de passe',
                            'class'         => 'input_txt_large'
                        )
                    ),
                    'invalid_message'   => 'fos_user.password.mismatch',
                ))
				->add('enabled', 'checkbox', array(
						'label' => 'actif'
				))
                ->add('label', 'text', array(
                        'max_length'=> '35',
                        'label'     => 'Label',
                        'attr'      => array(
                            'placeholder'   => 'label',
                            'class'         => 'input_txt_large'
                        ),
                        'required'  => true,
                        'trim'      => true
                ));
    }

    public function getParent() {
	    return 'fos_user_registration';
    }


    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\User'
        ));
    }


	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setRequired('em');
	}


    public function getName() {
        return 'lci_user_registration';
    }


	// Fonction qui retourne les rôles définis en base de donnée
	private function fillRoles($em) {
		$tableau_des_roles = array();
		$tab_roles = $em->getRepository('LciBoilerBoxBundle:Role')->recuperationDesRoles();
		foreach($tab_roles as $key => $sous_tab_roles) {
			foreach ($sous_tab_roles as $key2 => $role) {
				$tableau_des_roles[$role] = strtolower(substr($role, 5));
			}
		}
		return $tableau_des_roles;
	}
}
