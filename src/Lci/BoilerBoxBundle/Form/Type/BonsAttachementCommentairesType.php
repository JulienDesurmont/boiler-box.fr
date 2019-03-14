<?php
namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionResolverInterface;
use Lci\BoilerBoxBundle\Form\Type\BonsAttachementType as BaseType;
use Doctrine\ORM\EntityRepository;


class BonsAttachementCommentairesType extends BaseType {
	/**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		parent::buildForm($builder, $options);
		
		$builder->remove('numeroBA')
				->remove('dateSignature')
				->remove('nomDuContact')
				->remove('emailContactClient')
        		->remove('userInitiateur')
        		->remove('user')
        		->remove('dateInitialisation')
        		->remove('numeroAffaire')
        		->remove('site')
        		->remove('fichiersPdf')
				 ->add('commentaires', 'textarea', array(
            		'label'         => 'Commentaires ...',
            		'label_attr'    => array ('class' => 'label_bigtext'),
            		'attr'          => array(
                						'cols'      => 65,
                						'rows'      => 6,
                						'placeholder' => 'Nouveaux commentaires ...',
                						'style'     => 'resize:none;'
            							),
					'data'			=> ''	
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
