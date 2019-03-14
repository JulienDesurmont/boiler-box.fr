<?php

namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Lci\BoilerBoxBundle\Form\Type\FichierJointType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Lci\BoilerBoxBundle\Entity\UserRepository;
use Lci\BoilerBoxBundle\Entity\EquipementRepository;
use Lci\BoilerBoxBundle\Entity\ModuleRepository;

class ProblemeTechniqueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('id', 'integer', array(
					'label_attr'	  => array('class' => 'identifiant cacher'),
					'attr'      	  => array('class' => 'identifiant cacher')
				))
				->add('module', 'entity', array(
					'label' 		  => 'Module(s)',
					'label_attr'	  => array('class' => 'label_smalltext'),
					'attr'			  => array('class' => 'input_select'),
					'class' 		  => 'LciBoilerBoxBundle:Module',
					'property' 		  => 'infoSelect',
					'multiple'		  => true,
					'required'		  => true,
					'query_builder'   => function (ModuleRepository $mr) {
                    	return $mr->createQueryBuilder('m')->where('m.actif = true')->orderBy('m.numero', 'ASC');
                    }
				))
                ->add('equipement', 'entity', array(
					'label'			  => "Equipement",
					'label_attr'	  => array('class' => 'label_smalltext'),
					'attr'      	  => array('class' => 'input_select'),
					'class' 		  => 'LciBoilerBoxBundle:Equipement',
					'property' 		  => 'type',
					'required'		  => true,
					'query_builder'   => function (EquipementRepository $er) {
                    	return $er->createQueryBuilder('e')->where('e.actif = true')->orderBy('e.type', 'ASC');
                    }
				))
				->add('bloquant', 'checkbox', array(
						'label'		  => 'Problème bloquant',
						'label_attr'  => array('class' => 'label_smalltext'),
						'attr'			  => array(
							'class'			=> 'input_checkbox'
						),
                      	'required'    => false
				))
				->add('dateSignalement', 'date', array(
					'label' 		  => 'Date de signalement',
					'label_attr'	  => array('class' => 'label_date'),
					'attr'      	  => array(
						'class' 		=> 'input_date',
						'placeholder' 	=> 'dd-mm-yyyy'
					),
					'format'		  => 'dd-MM-yyyy',
					'invalid_message' => 'Format de la date incorrect.',
					'widget' 		  => 'single_text'
				))
				 ->add('user', 'entity', array(
                    'label'     	  => 'Opérateur désigné',
                    'label_attr'	  => array('class' => 'label_smalltext'),
                    'attr'      	  => array('class' => 'input_select'),
					'class'     	  => 'LciBoilerBoxBundle:User',
					'property'  	  => 'username',
					'query_builder'   => function (UserRepository $ur) {
                    	return $ur->createQueryBuilder('u')->orderBy('u.username', 'ASC');
                    }
                ))
				->add('description', 'textarea', array(
                    'label'     	  => 'Description du problème',
					'label_attr'	  => array('class' => 'label_smalltext'),
					'attr'      	  => array(
						'class' 		=> 'frm_texte_box',
						'placeholder'	=> 'Description du problème'
					)
                ))
				->add('solution', 'textarea', array(
					'label'     	  => 'Solution retenue',
					'label_attr'	  => array('class' => 'label_smalltext'),
					'attr'      	  => array(
						'class' 		=> 'frm_texte_box',
						'placeholder'	=> 'Solution retenue'
					),
					'required' 		  => false,
				))
				->add('corrige', 'checkbox', array(
					'label'			  => 'Problème corrigé',
					'label_attr'	  => array('class' => 'label_smalltext'),
					'attr'			  => array(
						'class'			=> 'input_checkbox'
					),
					'required' 		  => false
				))
				->add('dateCorrection', 'date', array(
					'label'     	  => 'Date de résolution',
					'label_attr'	  => array('class' => 'label_date'),
					'attr'      	  => array(
						'class' 		=> 'input_date',
						'placeholder' 	=> 'dd-mm-yyyy'
					),
					'format'    	  => 'dd-MM-yyyy',
					'widget'    	  => 'single_text',
					'invalid_message' => 'Format de la date incorrect.',
					'required' 		  => false
				))
				->add('cloture', 'checkbox', array(
					'label'			  => 'Problème clos',
					'label_attr'	  => array('class' => 'label_smalltext'),
					'attr'			  => array(
						'class'			=> 'input_checkbox'
					),
					'required' 		  => false
				))
				->add('dateCloture', 'date', array(
					'label'			  => 'Date de clôture',
					'label_attr'	  => array('class' => 'label_date'),
					'attr'      	  => array(
						'class' 		=> 'input_date',
						'placeholder' 	=> 'dd-mm-yyyy'
					),
					'format'    	  => 'dd-MM-yyyy',
					'widget'    	  => 'single_text',
					'invalid_message' => 'Format de la date incorrect.',
					'required'  	  => false
				))
				->add('fichiersJoint', 'collection', array(
					'label'			=> 'Fichier(s) joint(s)',
					'label_attr'    => array('class' => 'label_smalltext'),
					'type'			=> new FichierJointType(),
					'allow_add'		=> true,
					'allow_delete'	=> true,
					'options'		=> array('data_class' => 'Lci\BoilerBoxBundle\Entity\FichierJoint')
				))
				;
    }
    

    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\ProblemeTechnique'
        ));
    }




    /**
     * @return string
    */
    public function getName(){
        return 'lci_boilerboxbundle_problemeTechnique';
    }
}
