<?php
// srv/www/htdocs/Symfony/src/Lci/UserBundle/Listener/detectLoginListener.php
namespace Lci\UserBundle\Listeners;

use Symfony\Component\Security\Core\Event\AuthenticationEvent;

class detectLoginListener {
    // Login à capturer
    protected $detectLogin;
    protected $dateConnexion;

    public function __construct(detectLogin $detectLogin) {
        $this->detectLogin = $detectLogin;
        $date = new \Datetime();
        $this->dateConnexion = $date->format('d-m-Y à H:i:s');
    }

    public function successLogin(AuthenticationEvent $event) {
		if ($_SERVER['PATH_INFO'] == '/login_check') {
        	$token = $event->getAuthenticationToken();
        	// Modification des informations
        	$this->detectLogin->enregistreConnexion('SUCCESS', $this->dateConnexion, $token);
		}
    }

    public function failedLogin(AuthenticationEvent $event) {
		if ($_SERVER['PATH_INFO'] == '/login_check') {
        	$token = $event->getAuthenticationException();
        	$this->detectLogin->enregistreConnexion('FAILED', $this->dateConnexion, $token);
		}
    }
}

