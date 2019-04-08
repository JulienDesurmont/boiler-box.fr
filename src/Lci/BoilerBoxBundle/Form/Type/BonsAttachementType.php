<?php
namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionResolverInterface;
use Doctrine\ORM\EntityRepository;




class BonsAttachementType extends AbstractType {
	/**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
	public function buildForm(FormBuilderInterface $builder, array $option) {
		$builder
        ->add('userInitiateur', 'entity', array (
            'class'           => 'LciBoilerBoxBundle:User',
            'required'        => true,
            'label'           => 'Initiateur du bon',
            'label_attr'      => array ('class' => 'label_smalltext'),
            'attr'            => array ('class' => 'smallselect'),
            'property'        => 'label',
            'query_builder'   => function(EntityRepository $er){
                return $er->createQueryBuilder('u')->orderBy('u.label', 'ASC');
            },
        ))
        ->add('user', 'entity', array (
            'class'           => 'LciBoilerBoxBundle:User',
            'required'        => true,
            'label'           => 'Intervenant',
            'label_attr'      => array ('class' => 'label_smalltext'),
			'attr'			  => array ('class' => 'smallselect'),
			'property'        => 'label',
			'query_builder'   => function(EntityRepository $er){
				return $er->createQueryBuilder('u')->orderBy('u.label', 'ASC');
			},
        ))
        ->add('dateInitialisation', 'date', array(
            'label'         => 'Date d\'initialisation du bon',
            'label_attr'    => array ('class' => 'label_smalltext'),
            'widget'        => 'single_text',
            'html5'         => false,
            'format'        => 'dd-MM-yyyy',
            'invalid_message' => 'Format de la date incorrect.',
            'attr'          => array(
                'class'         => 'smallinput',
                'placeholder'   => 'dd/mm/YYYY',
                'maxlength'     => 10
            )
        ))
		->add('numeroBA', 'text', array(
			'label' 		=> 'Numéro du bon d\'attachement',
			'label_attr'    => array ('class' => 'label_smalltext'),
			'required' 		=> true,
            'trim' 			=> true,
			'attr' 			=> array(
				'class' 		=> 'biginput centrer',
				'placeholder' 	=> 'XXXXXX',
				'maxlength'     => 6
			)
		))
		->add('numeroAffaire', 'text', array(
            'label' 		=> 'Numéro d\'affaire',
			'label_attr'    => array ('class' => 'label_smalltext'),
			'required' 		=> true,
            'trim' 			=> true,
            'attr' 			=> array(
				'class' 		=> 'biginput upper centrer',
				'maxlength' 	=> 7
            )
        ))
        ->add('site', 'entity', array(
            'label'         => 'Nom du site',
			'class'			=> 'LciBoilerBoxBundle:SiteBA',
			'property'		=> 'intitule',
			'query_builder'	=> function (EntityRepository $er) {
				return $er->createQueryBuilder('ba')
					->orderBy('ba.intitule', 'ASC');
			},
			'required'		=> true,
            'label_attr'    => array ('class' => 'label_smalltext'),
            'attr'          => array('class' => 'smallselect'),
        ))
		->add('dateSignature', 'date', array(
			'label' 		=> 'Date de la signature',
			'label_attr'    => array ('class' => 'label_smalltext'),
			'widget' 		=> 'single_text',
			'html5'			=> false,
			'format'        => 'dd-MM-yyyy',
            'invalid_message' => 'Format de la date incorrect.',
			'attr' 			=> array(
				'class' 		=> 'smallinput',
				'placeholder' 	=> 'dd/mm/YYYY',
				'maxlength' 	=> 10
			)
		))
		->add('nomDuContact', 'text', array(
			'label'			=> 'Nom du contact client',
			'label_attr'    => array ('class' => 'label_smalltext'),
            'required'      => true,
            'trim'          => true,
            'attr'          => array(
                'class'         => 'biginput lower centrer'
            )
		))
		->add('emailContactClient', 'text', array(
			'label' 		=> 'Email du contact',
			'label_attr'    => array ('class' => 'label_smalltext'),
			'required' 		=> true,
            'trim' 			=> true,
			'attr' 			=> array ( 
				'class' 		=> 'biginput',
				'placeholder' 	=> ''
			)
		))
		->add('fichiersPdf', 'collection', array(
			'type'          => new FichierType(),
			'label' 		=> 'Fichier(s) pdf du bon',
			'label_attr'    => array ('class' => 'label_smalltext'),
			'allow_add'		=> true,
			'allow_delete'	=> true,
			/* Option à ajouter pour résoudre l'erreur -> Warning: spl_object_hash() expects parameter 1 to be object, array given */
			'options'       => array('data_class' => 'Lci\BoilerBoxBundle\Entity\Fichier'),
			'required' 		=> true
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
