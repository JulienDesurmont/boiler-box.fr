<?php
namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormbuilderInterface;
use Lci\BoilerBoxBundle\Entity\SiteRepository;

class ListeDesSitesType extends AbstractType 
{
  public function buildForm(FormBuilderInterface $builder, array $options) 
  {
		$builder
		  ->add('site', 'entity', array(
			'label' 	=> 'Site',
			'class' 	=> 'Lci\BoilerBoxBundle\Entity\Site',
			'property' 	=> 'intitule',
			'query_builder' => function (SiteRepository $sr) {
                return $sr->createQueryBuilder('s')->where('s.typeSite = :site')->setParameter('site', 'site')->orderBy('s.intitule', 'ASC');
			},
			'mapped'	=> false
			))
		  ->add('submit', 'submit');
  }

  public function getName()
  {
	return 'ListeDesSites';
  }
}
