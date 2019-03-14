<?php
namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionResolverInterface;
use Doctrine\ORM\EntityRepository;


class BonsAttachementModificationType extends AbstractType {
	/**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
	public function buildForm(FormBuilderInterface $builder, array $option) {
		$builder
		->add('fichiersPdf', 'collection', array(
			'type'          => new FichierType(),
			'label_attr'    => array ('class' => 'label_smalltext'),
			'allow_add'		=> true,
			'allow_delete'	=> true,
			/* Option à ajouter pour résoudre l'erreur -> Warning: spl_object_hash() expects parameter 1 to be object, array given */
			'options'       => array('data_class' => 'Lci\BoilerBoxBundle\Entity\Fichier')
		));
	}


    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\BonsAttachement'
        ));
    }


    /**
     * @return string
    */
    public function getName(){
        return 'lci_boilerboxbundle_bonsAttachement';
    }

}
