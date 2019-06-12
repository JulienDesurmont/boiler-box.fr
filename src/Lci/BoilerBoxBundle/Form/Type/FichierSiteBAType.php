<?php
namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FichierSiteBAType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $option){
		$builder->add('file', 'file', array(
			'label' 		=> ' ',
			'label_attr'	=> array('class' => 'label_register')
		));
	}


    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\FichierSiteBA'
        ));
    }

	public function getName(){
		return 'lci_boilerboxbundle_fichierSiteBA';
	}
}
