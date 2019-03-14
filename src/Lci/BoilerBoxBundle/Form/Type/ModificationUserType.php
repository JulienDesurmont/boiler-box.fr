<?php
// Lci/BoilerBox/Form/Type/ModificationUserType.php
namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ModificationUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->remove('username')
                ->remove('plainPassword')
                ->remove('label');
    }

    public function getParent() {
	    return 'lci_user_registration';
    }

	public function getName() {
		 return 'lci_user_update';
	}

}
