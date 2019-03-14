<?php
namespace Lci\BoilerBoxBundle\Controller;

use Lci\BoilerBoxBundle\Entity\Validation;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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


public function supprimeUnFichierDeBonAction() {
    $id_bon = $_POST['identifiant_bon'];
    $id_fichier_bon = $_POST['identifiant_fichier'];
    $em = $this->getDoctrine()->getManager();
    $entity_fichier = $em->getRepository('LciBoilerBoxBundle:Fichier')->find($id_fichier_bon);
    $entity_bon = $em->getRepository('LciBoilerBoxBundle:BonsAttachement')->find($id_bon);
    $entity_bon->removeFichiersPdf($entity_fichier);
	// Suppression du fichier de la base (et du serveur avec l'evenement POSTREMOVE)
	$em->remove($entity_fichier);
	$em->flush();
	return new Response();
}





}