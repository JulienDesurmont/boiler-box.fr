<?php
namespace Lci\BoilerBoxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Lci\BoilerBoxBundle\Entity\Module;
use Lci\BoilerBoxBundle\Form\Type\ModuleType;

use Lci\BoilerBoxBundle\Entity\Equipement;
use Lci\BoilerBoxBundle\Form\Type\EquipementType;

use Lci\BoilerBoxBundle\Entity\ProblemeTechnique;
use Lci\BoilerBoxBundle\Form\Type\ProblemeTechniqueType;

use Lci\BoilerBoxBundle\Form\Type\CorrectionType;

use Symfony\Component\HttpFoundation\File\File;


class RegisterController extends Controller {
protected $nombre_problemes_affectes;

public function constructPerso() {
    $service_configuration = $this->get('lci_boilerbox.configuration');
    $this->nombre_problemes_affectes = $service_configuration->getNombreProblemesNonClos();
}

public function moduleRegistrationAction($provenance) {
	$this->constructPerso();
    $requete = $this->get('request');
	$entity_module = new Module();
	$form_module = $this->createForm(new ModuleType(), $entity_module);
	if ($requete->getMethod() == 'POST') {
		$form_module->handleRequest($requete);
		if ($form_module->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity_module);
			$em->flush();
			$requete->getSession()->getFlashBag()->add('info', 'Module '.$entity_module->getNumero().' enregistré.');
			switch ($this->getRequest()->getSession()->get('fromVar', 'none')){
				case 'accueil':
					return $this->redirect($this->generateUrl('lci_register_module', array(
                		'provenance' => $provenance
					)));
					break;
				case 'probleme':
					return $this->redirect($this->generateUrl('lci_register_problemeTechnique', array(
						'provenance' => $provenance
					)));
					break;
				case 'parc':
					return $this->redirect($this->generateUrl('lci_gestion_modules'));
					break;
			}
			//$entity_module = new Module();
			//$form_module = $this->createForm(new ModuleType(), $entity_module);
		} else {
			$requete->getSession()->getFlashBag()->add('info', 'Formulaire invalide : '.$form_module->getErrorsAsString());
		}
	}
	return $this->render('LciBoilerBoxBundle:Registration:newModule.html.twig', array(
		'form' => $form_module->createView(),
		'nombre_problemes_affectes' => $this->nombre_problemes_affectes,
		'provenance' => $provenance,
		'id' => $this->getRequest()->getSession()->get('variable_id')
	));
}

public function equipementRegistrationAction($provenance) {
	$this->constructPerso();
    $requete = $this->get('request');
    $entity_equipement = new Equipement();
    $form_equipement = $this->createForm(new EquipementType(), $entity_equipement);
    if ($requete->getMethod() == 'POST') {
        $form_equipement->handleRequest($requete);
        if ($form_equipement->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity_equipement);
            $em->flush();
			$requete->getSession()->getFlashBag()->add('info', 'Equipement '.$entity_equipement->getType().' enregistré.');
			switch ($this->getRequest()->getSession()->get('fromVar', 'none')){
                case 'accueil':
					return $this->redirect($this->generateUrl('lci_register_equipement', array(
                		'provenance' => $provenance
           	 		)));
                    break;
                case 'probleme':
                    return $this->redirect($this->generateUrl('lci_register_problemeTechnique', array(
                        'provenance' => $provenance
                    )));
                    break;
                case 'parc':
                    return $this->redirect($this->generateUrl('lci_gestion_equipements'));
                    break;
            }
            //return $this->redirect($this->generateUrl('lci_register_equipement', array(
            //    'provenance' => $provenance
            //)));
        } else {
            $requete->getSession()->getFlashBag()->add('info', 'Formulaire invalide : '.$form_equipement->getErrorsAsString());
        }
    }
    return $this->render('LciBoilerBoxBundle:Registration:newEquipement.html.twig', array(
        'form' => $form_equipement->createView(),
		'nombre_problemes_affectes' => $this->nombre_problemes_affectes,
		'provenance' => $provenance,
		'id' => $this->getRequest()->getSession()->get('variable_id')
    ));
}

public function problemeTechniqueRegistrationAction() {
	$provenance = $this->getRequest()->getSession()->get('provenance');
	$type_probleme = 'new';
	$this->constructPerso();
    $requete = $this->get('request');
	$session = $this->getRequest()->getSession();
	$this->getRequest()->getSession()->set('variable_id', null);
	if (! empty($_POST['id_probleme'])) {
		// Affichage du probleme depuis la page 'liste des problèmes'
		$type_probleme = 'update';
		$entity_probleme_technique = $this->getDoctrine()->getManager()->getRepository('LciBoilerBoxBundle:ProblemeTechnique')->find($_POST['id_probleme']);
		$session->set('id_entity_probleme_technique', $entity_probleme_technique->getId());
		$titre = 'Problème technique';
		// Si la requête ne provient pas du gestionnaire de parc : Affichage en mode texte de l'entité
		//	Ou si la requête provient du gestionnaire de parc et que la demande concerne l'affichage en mode Imprimable de la page.
		if ( (false === $this->get('security.authorization_checker')->isGranted('ROLE_RESPONSABLE_PARC')) || (isset($_POST['printed_version'])) ){
            return $this->redirect($this->generateUrl('lci_affiche_problemeTechnique'));
        }
	} else {
		if (isset($_POST['lci_boilerboxbundle_problemeTechnique']['id'])) {
			if ($_POST['lci_boilerboxbundle_problemeTechnique']['id'] != null) {
				// Update
				$type_probleme = 'update';
				$entity_probleme_technique = $this->getDoctrine()->getManager()->getRepository('LciBoilerBoxBundle:ProblemeTechnique')->find($_POST['lci_boilerboxbundle_problemeTechnique']['id']);	
				$session->set('id_entity_probleme_technique', $entity_probleme_technique->getId());
				$titre = 'Problème technique';
			} else {
				// New après submit en erreur
				$entity_probleme_technique = new ProblemeTechnique();
				$titre = 'Nouveau problème technique';
			}
		} else {
			// Nouveau problèeme technique 
    		$entity_probleme_technique = new ProblemeTechnique();
			$entity_probleme_technique->setDateSignalement(new \Datetime());
			$entity_module = $this->getDoctrine()->getManager()->getRepository('LciBoilerBoxBundle:Module')->myFindFirst();
			$entity_probleme_technique->addModule($entity_module);
			$titre = 'Nouveau problème technique';
		}
	}
	$em = $this->getDoctrine()->getManager();
    $form_probleme_technique = $this->createForm(new ProblemeTechniqueType(), $entity_probleme_technique);
    if ($requete->getMethod() == 'POST') {
		if (! isset($_POST['id_probleme'])) {
        	$form_probleme_technique->handleRequest($requete);
        	if ($form_probleme_technique->isValid()) {
				$tab_fichiers_joints = array();
				foreach ($entity_probleme_technique->getFichiersJoint() as $fichier_joint){
					$em->persist($fichier_joint);
					$em->flush();
					$tab_fichiers_joints[] = $fichier_joint;
				}

				// Si il s'agit de la création d'un nouveau ticket : // Création d'un ticket par module
				if ($_POST['lci_boilerboxbundle_problemeTechnique']['id'] == null ) {
					$tab_ticket = array();
					foreach ($entity_probleme_technique->getModule() as $module) {
						$tab_ticket[] = clone($entity_probleme_technique);
						$index_ticket = count($tab_ticket) - 1;
						$tab_ticket[$index_ticket]->clearModule();
						$tab_ticket[$index_ticket]->addModule($module);
						foreach ($tab_fichiers_joints as $fichier_joint){
							$tab_ticket[$index_ticket]->addReverseFichierJoint($fichier_joint);
						}
						$em->persist($tab_ticket[$index_ticket]);
						$em->flush();
					}
				} else {
					foreach ($tab_fichiers_joints as $fichier_joint){
                        $entity_probleme_technique->addReverseFichierJoint($fichier_joint);
                    }
					// Si il s'agit de la modification d'un ticket existant
					$em->persist($entity_probleme_technique);
					$em->flush();
				}

				// Envoi de l'email à l'intervenant si ce n'est pas un intervenant générique 'débutant par 0_'
				if ($type_probleme == "new") {
					if (substr($entity_probleme_technique->getUser()->getUsername(), 0, 2) != '0_') {
						$this->sendEmailProblemeTechnique($entity_probleme_technique, "Un problème technique vous a été affecté");
						$requete->getSession()->getFlashBag()->add('info', 'Problème technique enregistré. Un mail a été envoyé à '.$entity_probleme_technique->getUser()->getEmail().'.');
					} else {
						$requete->getSession()->getFlashBag()->add('info', 'Problème technique enregistré.');
					}
					return $this->redirect($this->generateUrl('lci_affiche_problemes'));
				}
				if ($type_probleme == "update") {
					if (substr($entity_probleme_technique->getUser()->getUsername(), 0, 2) != '0_') {
						$this->sendEmailProblemeTechnique($entity_probleme_technique, "Une mise à jour a été apportée sur le problème technique");
						$requete->getSession()->getFlashBag()->add('info', 'Problème technique mis à jour. Un mail a été envoyé à '.$entity_probleme_technique->getUser()->getEmail().'.');
					} else {
                        $requete->getSession()->getFlashBag()->add('info', 'Problème technique enregistré.');
                    }
					return $this->redirect($this->generateUrl('lci_tri_recherche_problemes'));
				}
        	    return $this->redirect($this->generateUrl('lci_affiche_problemes'));
        	} else {
				$requete->getSession()->getFlashBag()->add('info', 'Formulaire invalide : '.$form_probleme_technique->getErrorsAsString());
        	}
		} else {
			$this->getRequest()->getSession()->set('variable_id', $_POST['id_probleme']);
		}	
    }

	if ($type_probleme == "update") {	
		foreach($entity_probleme_technique->getFichiersJoint() as $fichier_joint){
			// Création des objet uploadedFile à partir des informations de type string stockées dans l'objet Fichier
			if (is_file($fichier_joint->getPath())){
				// Si le champs alt du fichier débute par removed : renommage
				if (substr($fichier_joint->getAlt(), 0, 8) == 'removed_'){
					$fichier_joint->setAlt(substr($fichier_joint->getAlt(), 8));	
					$this->getDoctrine()->getManager()->flush();
				}
				$fichier_joint->setFile(new File($fichier_joint->getPath()));
			} else {
				// Si le fichier n'existe pas : Modification du champs alt si il ne commence pas par removed_
				if (substr($fichier_joint->getAlt(), 0, 8) != 'removed_') {
					$fichier_joint->setAlt("removed_".$fichier_joint->getAlt());
					$this->getDoctrine()->getManager()->flush();
				}
			}
        }
	}
    return $this->render('LciBoilerBoxBundle:Registration:newProblemeTechnique.html.twig', array(
        'form' => $form_probleme_technique->createView(),
		'titre' => $titre,
		'type_probleme' => $type_probleme,
		'entity_probleme' => $entity_probleme_technique,
		'nombre_problemes_affectes' => $this->nombre_problemes_affectes,
		'provenance' => $provenance
    ));
}

// Fonction qui propose le téléchargement d'une pièce jointe
public function downloadFileAction(){
	$chemin_fichier = $_POST['fileUrl'];
	$nom_fichier = $_POST['fileName'];
	$id_fichier = $_POST['fileId']; 
    if (! is_file($chemin_fichier)) {
		// Si la pièce jointe n'est pas trouvée. Affichage d'une message d'erreur et renommage du champs alt en removed_alt
        $this->get("session")->getFlashBag()->add('info', "Fichier [$nom_fichier] non trouvé sur le serveur");
		$entity_fichier = $this->getDoctrine()->getRepository('LciBoilerBoxBundle:FichierJoint')->find($id_fichier);
		if (substr($entity_fichier->getAlt(), 0, 8) != 'removed_') {
			$entity_fichier->setAlt('removed_'.$entity_fichier->getAlt());
			$this->getDoctrine()->getManager()->flush();
		}
		$chemin_fichier = $entity_fichier->getUploadDir().'removedFile.txt';
	}
	$response = new Response();
	$response->setContent(file_get_contents($chemin_fichier));
	$response->headers->set('Content-Type', 'application/force-download');
	$response->headers->set('Content-disposition', 'attachment; filename='.$nom_fichier);
	$response->headers->set('Content-length', filesize($chemin_fichier));
	return $response;
}

public function sendEmailRappelProblemeTechniqueAction() {
    $entity_probleme_technique = $this->getDoctrine()->getManager()->getRepository('LciBoilerBoxBundle:ProblemeTechnique')->find($this->getRequest()->getSession()->get('id_entity_probleme_technique'));
    $this->sendEmailProblemeTechnique($entity_probleme_technique, "Rappel: Le problème technique attend votre intervention");
    return new Response();
}


private function sendEmailProblemeTechnique($entity_probleme, $titre){
    // Envoi de l'email à l'intervenant
    $service_mail = $this->get('lci_boilerbox.mailing');
	$liste_messages = array();
    $liste_messages[] = $titre;
    $liste_messages[] = '%T'."Référence";
    $liste_messages[] = $entity_probleme->getId();
    $liste_messages[] = '%T'."Description du problème";
    $liste_messages[] = nl2br($entity_probleme->getDescription());
	$liste_messages[] = '%T'."Date de signalement";
	$liste_messages[] = $entity_probleme->getDateSignalement()->format('d/m/Y');
	$liste_messages[] = '%T'."Module(s)";
    foreach ($entity_probleme->getModule() as $key => $module){
        $liste_messages[] = $module->getNumero()."<br />";
    }
	$liste_messages[] = '%T'."Equipement";
    $liste_messages[] = $entity_probleme->getEquipement()->getType();
    if ($entity_probleme->getCorrige() == true) {
		$liste_messages[] = '%T'."Correction";
        $message_correction = '%C'."Problème corrigé";
        if ($entity_probleme->getDateCorrection() != null) {
            $message_correction .= " le ".$entity_probleme->getDateCorrection()->format('d/m/Y');
        }
        $liste_messages[] = $message_correction;
    }
    if ($entity_probleme->getSolution() != null) {
        $liste_messages[] = '%T'."Solution";
		$liste_messages[] = nl2br($entity_probleme->getSolution());
    }
	$liste_messages[] = "<br /><br /><br />";
	$liste_messages[] = "Merci de bien vouloir m'informer de la prise en charge du problème ainsi que de sa résolution.<br /><br />";
	$liste_messages[] = "Cordialement.<br />";
	$liste_messages[] = "Responsable de parc.";
	$liste_messages[] = "<br /><br /><br />";
    $service_mail->sendProblemeTechniqueMail($this->getUser()->getEmail(), $entity_probleme->getUser()->getEmail(), $liste_messages);
	return 0;
}


}
