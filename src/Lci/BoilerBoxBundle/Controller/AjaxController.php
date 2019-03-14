<?php
namespace Lci\BoilerBoxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class AjaxController extends Controller {

// AJAX : Récupération des informations de connexion
public function getUserLogAction() {
    $session = $this->getRequest()->getSession();
    $userLog = $session->get('userLog',array());
    $login = $userLog['login'];
    $user = $this->container->get('doctrine')->getManager()->getRepository('LciBoilerBoxBundle:User')->findOneBy(array('username' => $userLog['login']));
    $liste_roles = $user->getRoles();
	/* Permet de changer les mots de passe Admin / Technicien et Client
	$pass = $this->container->get('doctrine')->getManager()->getRepository('LciBoilerBoxBundle:Zoneadmin')->findOneBy(array('login' => 'Admin'));	
	$pass->setPassword('@dm|n5667');
	$this->container->get('doctrine')->getManager()->flush();
	*/
    if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
        $password = $this->container->get('doctrine')->getManager()->getRepository('LciBoilerBoxBundle:Zoneadmin')->findOneBy(array('login' => 'Admin'))->getPassword();
        echo "Admin;$password";
    } elseif ($this->get('security.context')->isGranted('ROLE_TECHNICIEN')) {
        $password = $this->container->get('doctrine')->getManager()->getRepository('LciBoilerBoxBundle:Zoneadmin')->findOneBy(array('login' => 'Technicien'))->getPassword();
        echo "Technicien;$password";
    } elseif ($this->get('security.context')->isGranted('ROLE_USER')) {
        $password = $this->container->get('doctrine')->getManager()->getRepository('LciBoilerBoxBundle:Zoneadmin')->findOneBy(array('login' => 'Client'))->getPassword();
        echo "Client;$password";
    }
    return new Response();
}

// Fonction qui retourne la liste des sites auxquel un utilisateur a accés
// L'identifiant de l'utilisateur est passé en paramètre de l'appel AJAX
public function getUserSitesAction() {
    $id_user = $_POST['idUser'];
    $em = $this->getDoctrine()->getManager();
    $user = $em->getRepository('LciBoilerBoxBundle:User')->find($id_user);
    $user_sites = $user->getSite();
    $tab_entity_sites = array();
    foreach ($user_sites as $site) {
        $tab_entity_sites[$site->getId()] = '('.$site->getAffaire().') '.$site->getIntitule();
    }
    echo json_encode($tab_entity_sites);
    return new Response();
}

// Fonction qui retourne la liste des utilisateurs ayant accés à un site
// L'identifiant du site est passé en paramètre de l'appel AJAX
public function getSiteUsersAction() {
    $id_site = $_POST['idSite'];
    $em = $this->getDoctrine()->getManager();
    $site = $em->getRepository('LciBoilerBoxBundle:Site')->find($id_site);
    $site_users = $em->getRepository('LciBoilerBoxBundle:User')->findBySite($site);
    $tab_entity_users = array();
    foreach ($site_users as $user) {
        $tab_entity_users[$user->getId()] = $user->getLabel();
    }
    echo json_encode($tab_entity_users);
    return new Response();
}

public function sendEmailRappelProblemeTechniqueAction() {
	return $this->redirect($this->generateUrl('lci_mail_probleme_rappel'));
}
    

// Fonction qui change la variable de session changeFrom
public function changeFromVarAction(){
	if (isset($_GET['fromVar'])){
		$fromVar = $_GET['fromVar'];
		$this->getRequest()->getSession()->set('fromVar', $fromVar);
	}
	return new Response();
}


// Fonction qui change la variable de session provenance
public function changeVarProvenanceAction(){
    if (isset($_GET['provenance'])){
        $fromVar = $_GET['provenance'];
        $this->getRequest()->getSession()->set('provenance', $fromVar);
    }
    return new Response();
}


// Fonction qui retourne la valeur d'une variable de session
public function getVariableDeSessionAction() {
	if (isset($_GET['variable'])) {
		$variable = $_GET['variable'];
		echo json_encode($this->getRequest()->getSession()->get($variable));
	}
	return new Response();
}


// Utilitaire NETCAT : Affiche la liste des sites avec un indicateur indiquant la disponibilité du site
public function refreshASiteStatutAction() {
	$idSite = $_POST['idSite'];
    $delais_netcat = 2;
    $dnsServer = '8.8.8.8';
    $tab_details = array();

    // **** Mise à jour de la valeur du paramètre : date du dernier test de disponibilité des sites.
    $em = $this->getDoctrine()->getManager();
    $date_du_jour = new \Datetime();
    // **** Recherche de la liste des sites enregistrés en base de donnée
    $entity_site = $em->getRepository('LciBoilerBoxBundle:Site')->find($idSite);

    // Inscription de la date du test dans l'entité site
    $dateAccess = new \Datetime();
    $entity_site->setDateAccess($dateAccess);

    if ( ($entity_site->getAccesDistant() == true) && ($entity_site->getConfigBoilerBox() == true) ) {
        $tab_param_url = $this->recuperationSiteUrl($entity_site->getUrl());
		// **** Récupération de l'adresse ip
        $commande_adresse_ip_site = "host -t A ".$tab_param_url['url']." ".$dnsServer." | grep 'has address' | awk -F' ' '{print $4}'";
        $adresse_ip_site = exec($commande_adresse_ip_site, $tab_adresse_ip, $retour_commande);
        // **** On fait la recherche si une adresse ip est trouvée
        if ($adresse_ip_site != '') {
			// **** Test de ping (commande netcat) de l'adresse ip
        	$commande_netcat = "nc -v -n -z -w $delais_netcat $adresse_ip_site ".$tab_param_url['port'];
        	$last_command_lign = exec($commande_netcat, $tab_netcat, $retour_commande_netcat);
        	if ($retour_commande_netcat == 0) {
        	    $tab_details[$entity_site->getId()]['statut'] = 'ok';
				$tab_details[$entity_site->getId()]['detail'] = "<p>Success : $commande_netcat</p><span class='ok'>".$entity_site->getIntitule()." : Joignable sur le port ".$tab_param_url['port']."</span>";
				$entity_site->setDisponibilite(0);
        	} else {
        	    $tab_details[$entity_site->getId()]['statut'] = 'nok';
				$tab_details[$entity_site->getId()]['detail'] = "<p>Fail : $commande_netcat</p><span class='nok'>".$entity_site->getIntitule()." : Injoignable sur le port ".$tab_param_url['port']."</span>";
				$entity_site->setDisponibilite(2);
        	}
            $this->interpretation_netcat($entity_site, $retour_commande_netcat);
        } else {
			// Inaccessible car l'adresse ip n'est pas trouvée
			$entity_site->setDisponibilite(4);
			$tab_details[$entity_site->getId()]['detail'] = "<p>Url : ".$entity_site->getUrl()." inexistante</p><span class='nok'>Le site ".$entity_site->getAffaire()." n'est pas joignable depuis boiler-box</span>";
			$tab_details[$entity_site->getId()]['statut'] = 'inaccessible';
    	}
    } else {
		// Inaccessible car la configuration BoilerBox n'est pas faite OU l'accès distant est définit à FALSE
		if ($entity_site->getAccesDistant() == false) {
			$entity_site->setDisponibilite(4);
			$tab_details[$entity_site->getId()]['statut'] = 'inaccessible';
			$tab_details[$entity_site->getId()]['detail'] = "<p>Inaccessible : </p><span class='inaccessible'>".$entity_site->getIntitule()." : N'est pas accessible à distance</span>";
		} else {
			$entity_site->setDisponibilite(3);
			$tab_details[$entity_site->getId()]['statut'] = 'ecatcher';
			$tab_details[$entity_site->getId()]['detail'] = "<p>Joignable depuis Ecatcher : </p><span class='ecatcher'>".$entity_site->getIntitule()." : Est joignable seulement depuis Ecatcher</span>";
		}
    }
	$tab_details[$entity_site->getId()]['date_test'] = date('H\hi');
	$em->flush();
	return new JsonResponse($tab_details);
}


// Fonction qui prend en argument une url de type http://c671.boiler-box.fr/ (ou http://c714.boiler-box.fr:81/) et retourne l'url c671.boiler-box.fr (ou c714.boiler-box.fr)
private function recuperationSiteUrl($url) {
    $urlSite = $url;
    $pattern_live = '/^(http:\/\/.+?\/)/';
    if (preg_match($pattern_live, $url, $tabUrlLive)) {
        $urlSite = $tabUrlLive[1];
    }
    $tab_param_url = array();
    $pattern_url = '/^http:\/\/(.+?):?(\d*?)\/$/';
    if (preg_match($pattern_url, $urlSite, $tab_url)) {
        if ($tab_url[2] == null) {
            $tab_url[2] = 80;
        }
        $tab_param_url['url'] = $tab_url[1];
        $tab_param_url['port'] = $tab_url[2];
    } else {
        $tab_param_url['url']  = $url;
        $tab_param_url['port'] = 80;
    }
    return($tab_param_url);
}

private function interpretation_netcat($entitySite, $retour_commande_netcat) {
    //  Inscription de la date du test dans l'entité site
    $dateAccess = new \Datetime();
    // **** Pour inclure l'acces LIVE : $site_Live = $entitySite->getAffaire().'L';
    // **** Pour inclure l'acces LIVE : $entityLive = $this->container->get('doctrine')->getManager()->getRepository('LciBoilerBoxBundle:Site')->findOneByAffaire($site_Live);
    if ($retour_commande_netcat == 0) {
		$dateAccessSucceded = new \Datetime();
        $entitySite->setDisponibilite(0);
        $entitySite->setDateAccessSucceded($dateAccessSucceded);
        /*
		if ($entityLive != null) {
            $entityLive->setDisponibilite(0);
            $entityLive->setDateAccessSucceded($dateAccess);
        }
		*/
    } else {
        $entitySite->setDisponibilite(2);
        // **** Pour inclure l'acces LIVE : if ($entityLive != null) { $entityLive->setDisponibilite(2); }
    }
	$this->getDoctrine()->getManager()->flush();
    return(0);
}




}
