<?php

namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
	    $builder
				->add('intitule', 'text', array(
                    'max_length'    => 20,
                    'label'         => 'Intitulé',
                    'attr'		    => array (
                        'placeholder'   => 'Intitulé du site',
                        'class'         => 'input_txt_large'
                    ),
                    'required'      => true,
                    'trim'          => true
				))
                ->add('affaire', 'text', array(
                    'max_length'    => 10,
                    'label'         => 'Code affaire',
                    'attr'		    => array (
                        'placeholder'   => 'Code affaire',
                        'class'         => 'input_txt_large'
                    ),
                    'required'      => true,
                    'trim'          => true
				))
				->add('typeSite', 'choice', array(
					'label'			=> 'Type',
					'attr'          => array('class' => 'radio_smalltext'),
					'multiple'		=> false,
					'expanded'		=> false,
					'choices'		=> array(
							'site'			=> 'Site',
							'module'		=> 'Module',
							'live_site' 	=> 'Live de site',
							'live_module' 	=> 'Live de module'
					)	
				))
                ->add('url', 'text', array(
                    'max_length'    => 255,
                    'label'         => 'Url',
                    'attr'		    => array (
                        'placeholder'   => 'URL',
                        'class'         => 'input_txt_large'
                    ),
                    'required'      => true,
                    'trim'          => true
				))
				->add('accesDistant', 'choice', array(
					'label'			=> 'Site accessible à distance',
					'multiple'		=> false,
					'expanded'		=> true,
					'choices'		=> array(
										'0' => 'Non',
										'1'	=> 'Oui'
									),
					'attr'          => array('class' => 'radio_smalltext')
				))
				->add('connexion3g', 'choice', array(
					'label' 		=> 'Connexion 3G',
					'multiple'		=> false,
					'expanded'		=> true,
					'choices'		=> array(
										'0'	=> 'Non',
										'1'	=> 'Oui'
									),
					'attr'			=> array('class' => 'radio_smalltext') 
				))
				->add('connexionAdsl', 'choice', array(
                    'label'         => 'Connexion Adsl',
                    'multiple'      => false,
                    'expanded'      => true,
                    'choices'       => array(
                                        '0' => 'Non',
                                        '1' => 'Oui'
                                    ),
                    'attr'          => array('class' => 'radio_smalltext')
                ))
				->add('configBoilerBox', 'choice', array(
					'label'		=> "Configuration d'accès BoilerBox",
					'expanded'	=> false,
					'multiple'	=> false,
					'choices'	=> array(
						'O' => 'Incorrecte',
						'1' => 'Correcte' 
					)
				));
    }

    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\Site'
        ));
    }


    /**
     * @return string
     */
    public function getName(){
        return 'lci_boilerboxbundle_site';
    }
}
