<?php

namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lci\BoilerBoxBundle\Entity\UserRepository;
use Lci\BoilerBoxBundle\Entity\EquipementRepository;
use Lci\BoilerBoxBundle\Entity\ModuleRepository;


class ObjRechercheProblemeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('dateDebut', 'date', array(
					'label' 	        => 'Date de début',
					'label_attr'	    => array('class' => 'label_date'),
					'attr'      	    => array(
						'class' 		    => 'input_date',
						'placeholder' 	    => 'dd-mm-yyyy'
					),
					'format'	        => 'dd-MM-yyyy',
					'invalid_message'   => 'Format incorrect',
					'widget'            => 'single_text'
				))
                ->add('dateFin', 'date', array(
					'label'		        => 'Date de fin',
					'label_attr'	    => array('class' => 'label_date'),
					'attr'      	    => array(
						'class' 		    => 'input_date',
						'placeholder' 	    => 'dd-mm-yyyy'
					),
					'format'	        => 'dd-MM-yyyy',
					'invalid_message'   => 'Format incorrect',
					'widget'            => 'single_text'
				))
				->add('intervenant', 'entity', array(
					'label' 	        => 'Opérateur',
					'label_attr'        => array('class' => 'label_smalltext'),
					'attr'              => array('class' => 'input_txt'),
					'class'             => 'LciBoilerBoxBundle:User',
					'property'	        => 'username',
					'mapped' 	        => false,
					'query_builder'     => function (UserRepository $ur) {
                        return $ur->createQueryBuilder('u')->orderBy('u.username', 'ASC');
                    }
				))
				->add('module', 'entity', array(
                    'label'             => 'Module',
                    'label_attr'        => array('class' => 'label_smalltext'),
                    'attr'              => array('class' => 'input_txt'),
                    'class'             => 'LciBoilerBoxBundle:Module',
                    'property'          => 'infoSelect',
					'mapped'            => false,
					'query_builder'     => function (ModuleRepository $mr) {
                        return $mr->createQueryBuilder('m')->where('m.actif = true')->orderBy('m.numero', 'ASC');
                    }
				))
				->add('equipement', 'entity', array(
                    'label'             => "Type d'équipement",
                    'label_attr'        => array('class' => 'label_smalltext'),
                    'attr'              => array('class' => 'input_txt'),
                    'class'             => 'LciBoilerBoxBundle:Equipement',
                    'property'          => 'type',
                    'mapped'            => false,
                    'query_builder'     => function (EquipementRepository $er) {
                        return $er->createQueryBuilder('e')->orderBy('e.type', 'ASC');
                    }
                ))
				->add('reference', 'number', array(
					'label'		        => 'Référence',
					'label_attr'        => array('class' => 'label_smalltext'),
					'invalid_message'   => 'Valeur incorrecte',
					'attr'              => array('class' => 'input_date')
				))
				->add('type', 'text', array(
					'label'             => 'Type',
                    'label_attr'        => array('class' => 'label_smalltext'),
                    'invalid_message'   => 'Valeur incorrecte',
                    'attr'              => array('class' => 'input_date')
                ))
				->add('chk_intervenant', 'checkbox', array(
                    'label'             => 'Opérateur',
					'label_attr'        => array('class' => 'label_smalltext'),
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
                    'mapped'            => false
                ))
                ->add('chk_module', 'checkbox', array(
                    'label'             => 'Module',
					'label_attr'        => array('class' => 'label_smalltext'),
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
                    'mapped'            => false
                ))
				->add('chk_equipement', 'checkbox', array(
                    'label'             => 'Equipement',
					'label_attr'        => array('class' => 'label_smalltext'),
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
                    'mapped'            => false
                ))
				->add('corrige', 'checkbox', array(
					'label'             => 'Problèmes corrigés',
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
					'label_attr'        => array('class' => 'label_smalltext')
				))
                ->add('nonCorrige', 'checkbox', array(
                    'label'             => 'Problèmes non corrigés',
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
                    'label_attr'        => array('class' => 'label_smalltext')
                ))
				->add('cloture', 'checkbox', array(
					'label'             => 'Problèmes clos',
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
					'label_attr'        => array('class' => 'label_smalltext')
				))
                ->add('nonCloture', 'checkbox', array(
                    'label'             => 'Problèmes non clos',
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
                    'label_attr'        => array('class' => 'label_smalltext')
                ))
				->add('bloquant', 'checkbox', array(
					'label'             => 'Problèmes bloquants',
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
					'label_attr'        => array('class' => 'label_smalltext')
				))
                ->add('nonBloquant', 'checkbox', array(
                    'label'             => 'Problèmes non bloquants',
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
                    'label_attr'        => array('class' => 'label_smalltext')
                ))
				->add('present', 'checkbox', array(
					'label'             => 'Modules présents',
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
					'label_attr'        => array('class' => 'label_smalltext')
				))
                ->add('nonPresent', 'checkbox', array(
                    'label'             => 'Modules non présents',
					'attr'			    => array(
						'class'			    => 'input_checkbox'
					),
                    'label_attr'        => array('class' => 'label_smalltext')
                ))
				;
    }
    


    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\ObjRechercheProbleme'
        ));
    }


    /**
     * @return string
    */
    public function getName(){
        return 'lci_boilerboxbundle_ObjRechercheProbleme';
    }
}
