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
                    'max_length'    => 20,
                    'label'         => 'Intitulé',
					'label_attr'    => array ('class' => 'label_smalltext'),
					'attr'          => array(
                		'class'         => 'biginput centrer',
                		'placeholder'   => 'Intitulé du nouveau site'
            		),
                    'required'      => true,
                    'trim'          => true
				));
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
