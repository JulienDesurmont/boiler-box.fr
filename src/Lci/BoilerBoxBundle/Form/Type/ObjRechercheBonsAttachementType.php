<?php
namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionResolverInterface;
use Doctrine\ORM\EntityRepository;

class ObjRechercheBonsAttachementType extends AbstractType {
	/**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
	public function buildForm(FormBuilderInterface $builder, array $option) {
		$builder
        ->add('user', 'entity', array(
            'class'           => 'LciBoilerBoxBundle:User',
            'label'           => 'Intervenant',
            'label_attr'      => array('class' => 'label_smalltext'),
			'attr'			  => array(
				'class' 		=> 'smallselect',
			),
			'property'        => 'label',
			'placeholder'   => 'Tout intervenant',
			'query_builder'   => function(EntityRepository $er){
				return $er->createQueryBuilder('u')->orderBy('u.label', 'ASC');
			}
        ))
        ->add('userInitiateur', 'entity', array(
            'class'           => 'LciBoilerBoxBundle:User',
            'label'           => 'Initiateur du bon',
            'label_attr'      => array('class' => 'label_smalltext'),
            'attr'            => array(
                'class'         => 'smallselect',
            ),
            'property'        => 'label',
            'placeholder'   => 'Tout initiateur',
            'query_builder'   => function(EntityRepository $er){
                return $er->createQueryBuilder('u')->orderBy('u.label', 'ASC');
            }
        ))
		->add('numeroAffaire', 'text', array(
            'label' 	 => 'Numéro d\'affaire',
			'label_attr' => array('class' => 'label_smalltext'),
			'trim' 		 => true,
            'attr' 		 => array(
				'class' 	  => 'biginput upper centrer',
                'placeholder' => 'XXXXXX',
				'maxlength'   => 7
            ),
        ))
       ->add('site', 'entity', array(
			'label' => 'Nom du site',
			'class' => 'LciBoilerBoxBundle:SiteBA',
			'property'	=> 'intitule',
			'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ba')
                    ->orderBy('ba.intitule', 'ASC');
            },
			'required'      => true,
            'label_attr'    => array ('class' => 'label_smalltext'),
            'attr'          => array('class' => 'smallselect'),
			'placeholder'   => 'Tout site'
		))
        ->add('nomDuContact', 'text', array(
            'label'         => 'Nom du contact',
            'label_attr'    => array ('class' => 'label_smalltext'),
            'required'      => true,
            'trim'          => true,
            'attr'          => array(
                'class'         => 'biginput centrer'
            )
        ))
	 	->add('saisie', 'choice', array(
			'label'			=> 'Type de bon',
			'label_attr' 	=> array('class' => 'label_smalltext'),
			'expanded'		=> true,
			'multiple'		=> false,
			'choices'		=> array(
				null	=> 'Tous type de bons',
				true 	=> 'Bons saisis',
				false 	=> 'Bons non saisis'
			),
            'required'    	=> false
		))
		->add('dateMin', 'date', array(
            'label' 	 => 'Signé (entre) le ',
            'label_attr' => array('class' => 'label_smalltext'),
            'widget' 	 => 'single_text',
            'input' 	 => 'string',
            'format'     => 'dd-MM-yyyy',
            'invalid_message' => 'Format de la date incorrect.',
            'attr' 		 => array(
                'class' 	  => 'smalldate',
                'placeholder' => 'dd/mm/YYYY',
				'maxlength'   => 10
            )
        ))
		->add('dateMax', 'date', array(
            'label' 	 => 'et',
            'label_attr' => array('class' => 'label_smalltext'),
            'widget' 	 => 'single_text',
            'input' 	 => 'string',
            'format'     => 'dd-MM-yyyy',
            'invalid_message' => 'Format de la date incorrect.',
            'attr' 		 => array(
                'class' 	  => 'smalldate',
                'placeholder' => 'dd/mm/YYYY',
				'maxlength'   => 10
            )
        ))
        ->add('dateMinInitialisation', 'date', array(
            'label'      => 'Initialisé (entre) le ',
            'label_attr' => array('class' => 'label_smalltext'),
            'widget'     => 'single_text',
            'input'      => 'string',
            'format'     => 'dd-MM-yyyy',
            'invalid_message' => 'Format de la date incorrect.',
            'attr'       => array(
                'class'       => 'smalldate',
                'placeholder' => 'dd/mm/YYYY',
                'maxlength'   => 10
            )
        ))
        ->add('dateMaxInitialisation', 'date', array(
            'label'      => 'et',
            'label_attr' => array('class' => 'label_smalltext'),
            'widget'     => 'single_text',
            'input'      => 'string',
            'format'     => 'dd-MM-yyyy',
            'invalid_message' => 'Format de la date incorrect.',
            'attr'       => array(
                'class'       => 'smalldate',
                'placeholder' => 'dd/mm/YYYY',
                'maxlength'   => 10
            )
        ))
        ->add('valideur', 'entity', array(
            'class'           => 'LciBoilerBoxBundle:User',
            'label'           => 'Validé par',
            'label_attr'      => array('class' => 'label_smalltext'),
            'attr'            => array(
                'class'         => 'smallselect',
            ),
            'property'        => 'label',
            'placeholder'   => 'Tout valideur',
            'query_builder'   => function(EntityRepository $er){
                return $er->createQueryBuilder('u')->orderBy('u.label', 'ASC');
            }
        ))
		->add('validationTechnique', 'checkbox', array(
			'label'       => 'Technique',
            'label_attr'  => array('class' => 'label_smalltext'),
            'attr'        => array(
            	'class'       => 'input_checkbox'
            ),
            'required'    => false
        ))
        ->add('validationHoraire', 'checkbox', array(
            'label'       => 'Horaire',
            'label_attr'  => array('class' => 'label_smalltext'),
            'attr'        => array(
                'class'       => 'input_checkbox'
            ),
            'required'    => false
        ))
        ->add('validationSAV', 'checkbox', array(
            'label'       => 'SAV',
            'label_attr'  => array('class' => 'label_smalltext'),
            'attr'        => array(
                'class'       => 'input_checkbox'
            ),
            'required'    => false
        ))
        ->add('validationFacturation', 'checkbox', array(
            'label'       => 'Facturation',
            'label_attr'  => array('class' => 'label_smalltext'),
            'attr'        => array(
                'class'       => 'input_checkbox'
            ),
            'required'    => false
        ))
		->add('sensValidation', 'choice', array(
			'label'		=> 'Validation',
			'choices'	=> array(
				null  => 'Aucune',
				true  => 'Bons validés',
				false => 'Bon non validés'
			),
			'expanded'	=> true,
			'multiple'	=> false
		));
	}



    /*
     * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\ObjRechercheBonsAttachement'
        ));
    }


    /**
     * @return string
    */
    public function getName(){
        return 'lci_boilerboxbundle_rechercheBonsAttachement';
    }

}
