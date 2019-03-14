<?php
namespace Lci\UserBundle

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LciUserBundle extends Bundle {
	public function getParent() {
		return 'FOSUserBundle';
	}
}
