<?php

namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EquipementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('id', 'integer', array (
                    'label_attr'      => array ('class' => 'identifiant'),
                    'attr'            => array ('class' => 'identifiant')
            	))
				->add('type', 'text', array(
					'label'   	=> "Type d'equipement",
                    'label_attr'=> array('class' => 'label_smalltext'),
                    'required' 	=> true,
                    'trim'     	=> true,
                    'attr'      => array(
                        'placeholder' => "Type d'équipement",
                        'class' => 'input_txt_large'
					)
                ))
            	->add('actif', 'checkbox', array (
                	'required'        => false,
                	'label'           => 'Equipement actif',
                	'label_attr'      => array ('class' => 'label_smalltext'),
                	'attr'            => array ('class' => 'checkbox')
            	));
    }
    

    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\Equipement'
        ));
    }

    /**
     * @return string
    */
    public function getName(){
        return 'lci_boilerboxbundle_equipement';
    }
}
