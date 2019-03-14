<?php

namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModuleType extends AbstractType
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
			->add('numero', 'integer', array (
                'required' 	      => true,
                'label'   	      => 'Numéro du module',
                'label_attr'      => array ('class' => 'label_smalltext'),
                'attr'            => array (
                    'min'           => 0,
                    'placeholder'   => 'Numéro du module',
                    'class'         => 'input_txt_large'
                )
			))
			->add('nom', 'text', array (
                'label'	          => 'Nom du module',
				'label_attr'      => array ('class' => 'label_smalltext'),
				'attr'		      => array (
                    'placeholder'   => 'Nom',
                    'class'         => 'input_txt_large'
                )	
			))
            ->add('type', 'text', array (
                'label'           => 'Type',
                'label_attr'      => array ('class' => 'label_smalltext'),
                'attr'            => array (
                    'placeholder'   => 'Type',
                    'class'         => 'input_txt_large'
                )
            ))
			->add('present', 'checkbox', array (
                'required'        => false,
				'label'           => 'Module présent',
                'label_attr'      => array ('class' => 'label_smalltext'),
                'attr'            => array ('class' => 'checkbox')
			))
			->add('site', 'entity', array (
				'class'			  => 'LciBoilerBoxBundle:Site',
				'required'		  => false,
				'label'           => 'Destination',
				'label_attr'      => array ('class' => 'label_smalltext'),
				'property'		  => 'affaire'
			))
            ->add('actif', 'checkbox', array (
                'required'        => false,
                'label'           => 'Module actif',
                'label_attr'      => array ('class' => 'label_smalltext'),
                'attr'            => array ('class' => 'checkbox')
            ))
            ->add('dateMouvement', 'date', array (
                'label'           => 'Depuis le ',
                'label_attr'      => array ('class' => 'label_date'),
                'attr'            => array (
                    'class'         => 'input_date',
                    'paceholder'    => 'dd-mm-yyyy'
                ),
                'format'          => 'dd-MM-yyyy',
                'invalid_message'   => 'Format de la date incorrect.',
                'widget'            => 'single_text'
            ));
    }


    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\Module'
        ));
    }

    /**
     * @return string
    */
    public function getName(){
        return 'lci_boilerboxbundle_module';
    }
}
