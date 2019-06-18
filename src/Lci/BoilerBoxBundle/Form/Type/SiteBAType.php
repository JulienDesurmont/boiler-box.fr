<?php

namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SiteBAType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
	    $builder
				->add('intitule', 'text', array(
                    'label'         => 'Nom du site',
					'label_attr'    => array (
						'class' 		=> 'label_smalltext',
					),
					'attr'          => array(
                		'class'         => 'biginput centrer',
                		'placeholder'   => 'Inscrire ici le nom du site',
						'style'         => 'width:100%'
            		),
                    'required'      => true,
                    'trim'          => true
				))
				->add('adresse', 'text', array(
                    'label'         => 'Adresse',
                    'label_attr'    => array(
                        'class'         => 'label_smalltext'
                    ),
                    'attr'          => array(
                        'class'         => 'biginput centrer',
                        'placeholder'   => "Inscrire ici l'adresse",
						'style'         => 'width:100%;'
                    ),
					'required'      => false
                ))
                ->add('lienGoogle', 'text', array(
                    'label'         => 'Url de Google map',
                    'label_attr'    => array(
                        'class'         => 'label_smalltext',
                    ),
                    'attr'          => array(
                        'class'         => 'biginput centrer',
                        'placeholder'   => "Copier ici l'url retournée par Google Map",
                        'style'         => 'width:100%'
                    ),
                    'required'      => false
                ))

                ->add('contact', 'text', array(
                    'label'         => 'Contact client',
                    'label_attr'    => array(
                        'class'         => 'label_smalltext'
                    ),
                    'attr'          => array(
                        'class'         => 'biginput centrer',
                        'placeholder'   => "Nom et prénom du contact client",
                        'style'         => 'width:100%'
                    ),
                    'required'      => false
                ))
                ->add('emailContact', 'email', array(
                    'label'         => 'Email du contact',
                    'label_attr'    => array(
                        'class'         => 'label_smalltext'
                    ),
                    'attr'          => array(
                        'class'         => 'biginput centrer',
                        'placeholder'   => "Inscrire ici l'email du contact",
                        'style'         => 'width:100%'
                    ),
                    'required'      => false
                ))
                ->add('telContact', 'text', array(
                    'label'         => 'Tél contact',
                    'label_attr'    => array(
                        'class'         => 'label_smalltext'
                    ),
                    'attr'          => array(
                        'class'         => 'biginput centrer',
                        'placeholder'   => "Inscrire ici le téléphone du contact",
                        'style'         => 'width:100%'
                    ),
                    'required'      => false
                ))

				->add('informationsClient', 'textarea', array(
                    'label'         => 'Informations',
                    'label_attr'    => array(
                        'class'         => 'label_smalltext'
                    ),
                    'attr'          => array(
						'rows'			=> 7,
                        'class'         => 'biginput centrer',
                        'placeholder'   => "Inscrire ici les informations complémentaires",
						'style'         => 'width:100%; resize:none'
                    ),
					'required'      => false
                ))
        		->add('fichiersJoint', 'collection', array(
            		'type'          => new FichierSiteBAType(),
            		'label'         => 'Fichier(s) joints au site',
            		'label_attr'    => array ('class' => 'label_smalltext'),
            		'allow_add'     => true,
            		'allow_delete'  => true,
            		/* Option à ajouter pour résoudre l'erreur -> Warning: spl_object_hash() expects parameter 1 to be object, array given */
            		'options'       => array('data_class' => 'Lci\BoilerBoxBundle\Entity\FichierSiteBA'),
            		'required'      => true
        		))
				->add('reset', 'reset');
    }

    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\SiteBA'
        ));
    }


    /**
     * @return string
     */
    public function getName(){
        return 'lci_boilerboxbundle_site_ba';
    }
}
