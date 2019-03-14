<?php
namespace Lci\BoilerBoxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class BoilerBoxController extends Controller {

/**
 * Récupérer la véritable adresse IP d'un visiteur
*/
function get_ip() {
	// IP si internet partagé
	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
	    return $_SERVER['HTTP_CLIENT_IP'];
	}
	// IP derrière un proxy
	elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	// Sinon : IP normale
	else {
	    return $_SERVER['REMOTE_ADDR'];
	}
}


public function indexAction($name) {
	if ($this->get('security.context')->isGranted('ROLE_AUTO_ENQUETE')) {
		return $this->redirectToRoute('lci_bons_externe_enquete');
	}
    return $this->render('LciBoilerBoxBundle:Connexion:login.html.twig', array('name' => $name));
	// Si l'utilisateur a les droits ROLE_AUTO_ENQUETE : On le redirige automatiquement vers la page de téléchargement des enquêtes
}



public function accesSiteAction() {
	if ($this->get('security.context')->isGranted('ROLE_AUTO_ENQUETE')) {
        return $this->redirectToRoute('lci_bons_externe_enquete');
    }
	$request = $this->get('request');
	if ($request->getMethod() == 'POST') {
		// redirection vers la page de login du site distant
		return new Response();
	}
	// Afficher l'adresse IP
	// Récupération de la liste des sites autorisés pour l'utilisateur connecté
	$session = $this->getRequest()->getSession();
	$userLog = $session->get('userLog', array());
	if (empty($userLog)) {
		return $this->redirect($this->generateUrl('fos_user_security_login'));	
	}
 	$user = $this->container->get('doctrine')->getManager()->getRepository('LciBoilerBoxBundle:User')->findOneBy(array('username'=>$userLog['login']));
	$liste_sites = array();
	$liste_sites = $user->getSite();
	$label = $user->getLabel();
    $service_configuration = $this->container->get('lci_boilerbox.configuration');
    $tab_date = $service_configuration->maj_date();
	$nombre_problemes_affectes = $service_configuration->getNombreProblemesNonClos();
	$date_test_acces_sites = $this->get('lci_boilerbox.configuration')->getEntiteDeConfiguration('date_test_de_disponibilite')->getValeur();
	return $this->render('LciBoilerBoxBundle:Connexion:logSites.html.twig',array(
	    'label' => $label,
	    'liste_sites' => $liste_sites,
        'leJour' => $tab_date['jour'],
        'lHeure' => $tab_date['heure'],
        'timestamp' => $tab_date['timestamp'],
		'nombre_problemes_affectes' => $nombre_problemes_affectes,
		'dateAccess' => $date_test_acces_sites
	));
}

// AJAX : Enregistrement des parametres de log
public function defineUserLogAction() {
    $session = $this->getRequest()->getSession();
	$userLog['login'] = $_POST['login'];
	$userLog['password'] = $_POST['password']; 
    $session->set('userLog', $userLog);
	return new Response();
}

}

