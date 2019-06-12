<?php
namespace Lci\BoilerBoxBundle\Controller;

use Lci\BoilerBoxBundle\Entity\Validation;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;


class AjaxBonsController extends Controller {

// Fonction qui modifie le paramètre EnqueteNecessaire d'un bon
public function setEnqueteAction() {
	$idBon = $_POST['identifiant'];
	$entity_bon = $this->getDoctrine()->getManager()->getRepository('LciBoilerBoxBundle:BonsAttachement')->find($idBon);
	if ($entity_bon->getEnqueteNecessaire() == true) {
		$entity_bon->setEnqueteNecessaire(false);
	} else {
		$entity_bon->setEnqueteNecessaire(true);
	}
	$this->getDoctrine()->getManager()->flush();
	return new Response();
}

// Fonction qui effectue la validation ou la suppression d'une ancienne validation d'une catégorie d'un bon
public function setValidationAction() {
    $idBon = $_POST['identifiant'];
	$type = $_POST['type'];
	$sens = $_POST['sens'];

	$em = $this->getDoctrine()->getManager();

	$entity_bon = $em->getRepository('LciBoilerBoxBundle:BonsAttachement')->find($idBon);
	$user = $this->get('security.context')->getToken()->getUser();

	// Si le sens est false c'est que la checkbox est décochée : On définit le champs valide à 0 pour signifier que l'entité Validation est une Dé-Validation
	if ($sens == 'false') {
		switch ($type) {
			case 'technique':
				$entity_validation = $this->devalidation($entity_bon->getValidationTechnique(), $user);
				break;
        	case 'sav':
				$entity_validation = $this->devalidation($entity_bon->getValidationSAV(), $user);
            	break;
        	case 'horaire':
				$entity_validation = $this->devalidation($entity_bon->getValidationHoraire(), $user);
        	    break;
        	case 'facturation':
				$entity_validation = $this->devalidation($entity_bon->getValidationFacturation(), $user);
        	    break;
        	default:
        	    break;
		}
	} else {
		$entity_validation = new Validation();
		$entity_validation->setValide(true);
        $entity_validation->setDateDeValidation(new \Datetime);
		$entity_validation->setType($type);
		$entity_validation->setUser($user);

		switch ($type) {
			case 'technique':
				$entity_bon->setValidationTechnique($entity_validation);
				break;
			case 'sav':
				$entity_bon->setValidationSAV($entity_validation);
				break;
			case 'horaire':
				$entity_bon->setValidationHoraire($entity_validation);
				break;
			case 'facturation':
				$entity_bon->setValidationFacturation($entity_validation);
				break;
			default:
				break;
		}
	    $em->persist($entity_bon);
	}
	$em->flush();
    return new Response();
}

// Fonction qui enregistre les informations de dévalidation 
private function devalidation($entity_validation, $entity_user) {
	$entity_validation->setValide(false);
	$entity_validation->setDateDeValidation(new \Datetime());
	$entity_validation->setUser($entity_user);
	return $entity_validation;
}


public function archiveUnFichierDeBonAction() {
    $id_fichier_bon = $_POST['identifiant_fichier'];
    $em = $this->getDoctrine()->getManager();
    $entity_fichier = $em->getRepository('LciBoilerBoxBundle:Fichier')->find($id_fichier_bon);
	if ($entity_fichier->getArchive() == false) {
		$message_archivage = "Archivé par ".$this->get('security.context')->getToken()->getUser()->getLabel()." le ".date('d/m/Y à H:i');
		$entity_fichier->setArchive(true);
		$entity_fichier->setInformations($message_archivage);
	} else {
		$message_archivage = "Désarchivé par ".$this->get('security.context')->getToken()->getUser()->getLabel()." le ".date('d/m/Y à H:i');
		$entity_fichier->setArchive(false);
		$entity_fichier->setInformations($message_archivage);
	}
	$em->flush();
	return new Response();
}


public function getSiteBAEntityAction() {
	$tab_fichier = null;
	if (isset($_POST['id_site_ba'])){
		$id_site_ba = $_POST['id_site_ba'];
	} else {
		$id_site_ba = 1;
	}
	$em = $this->getDoctrine()->getManager();
	$entity_siteba = $em->getRepository('LciBoilerBoxBundle:SiteBA')->find($id_site_ba);

	$tab_siteba[] = $entity_siteba->getId();
    $tab_siteba[] = $entity_siteba->getIntitule();
	$tab_siteba[] = $entity_siteba->getAdresse();
	$tab_siteba[] = $entity_siteba->getLienGoogle();
	$tab_siteba[] = $entity_siteba->getContact();
	$tab_siteba[] = $entity_siteba->getEmailContact();
	$tab_siteba[] = $entity_siteba->getTelContact();
	$tab_siteba[] = $entity_siteba->getInformationsClient();
	foreach ($entity_siteba->getFichiersJoint() as $ent_fichier) {
		$tab_fichier[] = $ent_fichier->getAlt();
	}
	if ($tab_fichier != null) {
		$tab_siteba[] = $tab_fichier;
	}
	
	echo json_encode($tab_siteba);
	return new Response;
}





}
