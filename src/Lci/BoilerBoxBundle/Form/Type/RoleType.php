<?php
namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RoleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('role', 'text', array(
					'label'   	=> "Role",
                ))
            	->add('description', 'textarea', array (
                	'label'           => 'Description',
                   	'attr'            => array(
                        'class'         => 'frm_texte_box',
                        'placeholder'   => 'Description du rôle'
                    )
            	))
				->add('submit', 'submit');
    }
    

    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\Role'
        ));
    }

    /**
     * @return string
    */
    public function getName(){
        return 'lci_boilerboxbundle_role';
    }
}
