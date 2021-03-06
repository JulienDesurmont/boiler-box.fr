<?php
namespace Lci\BoilerBoxBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BonsAttachementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BonsAttachementRepository extends EntityRepository
{

    // Fonction appelée par défaut pour les techniciens. Affiche l'ensemble de leur bons non saisis
    public function myFindByUser($User) {
		$qb = $this->createQueryBuilder('b');
		$qb	->leftJoin('b.user', 'u')
			->where('u = :intervenant')
			->setParameter('intervenant', $User); 
		$qb->andWhere($qb->expr()->orX( $qb->expr()->like('b.numeroBA',':empty'),
										$qb->expr()->isNull('b.numeroBA')))
			->setParameter(':empty', '');

		return $qb->getQuery()->getResult();
    }



    // Fonction utilisée pour la recherche des bons
    public function rechercheDesBons($entity_objRechercheBon) {
		$critereValidation = false;
        $queryBuilder = $this->createQueryBuilder('b');
		// Si un choix de service valideur est effectué, la validation s'effectue sur les bons validés, les bons non validés 
		// ou sur les bon validés dans le cas NULL (cad un valideur a été selectionné mais sans choix de service, on a alors considéré que la recherche s'effectue sur un des services  de la liste : on recherche
		// donc sur Tous les services avec un OU)
        if ($entity_objRechercheBon->getValidationFacturation() || $entity_objRechercheBon->getValidationTechnique() || $entity_objRechercheBon->getValidationSAV() || $entity_objRechercheBon->getValidationHoraire()) {
            if ($entity_objRechercheBon->getSensValidation() === 0){
				$sens_validation = 0;
            } elseif ($entity_objRechercheBon->getSensValidation() === 1) {
				$sens_validation = 1;
            } elseif ($entity_objRechercheBon->getSensValidation() === null) {
				$sens_validation = 1;
            }
        }

        if ($entity_objRechercheBon->getUser()) {
            $queryBuilder   ->leftJoin('b.user', 'u')
                            ->andWhere('u = :intervenant')
                            ->setParameter('intervenant', $entity_objRechercheBon->getUser());
        }

        if ($entity_objRechercheBon->getUserInitiateur()) {
            $queryBuilder   ->leftJoin('b.userInitiateur', 'ui')
                            ->andWhere('ui = :initiateur')
                            ->setParameter('initiateur', $entity_objRechercheBon->getUserInitiateur());
        }


        if ($entity_objRechercheBon->getNumeroAffaire()) {
            $queryBuilder   ->andWhere($queryBuilder->expr()->eq('b.numeroAffaire', ':numeroAffaire'))
                            ->setParameter('numeroAffaire', $entity_objRechercheBon->getNumeroAffaire());
        }
        if ($entity_objRechercheBon->getSite()) {
			$queryBuilder   ->leftJoin('b.site', 's')
							->andWhere('s = :site')
                            ->setParameter('site', $entity_objRechercheBon->getSite());
        }


		/************************************************** Recherche sur les validations de bons *********************************************************/
		$qbExprFacture 	= null;
		$qbExprHoraire 	= null;
		$qbExprSAV 		= null;
		$qbExprTechnique= null;
        $qbExprFactureNull  = null;
        $qbExprHoraireNull  = null;
        $qbExprSAVNull      = null;
        $qbExprTechniqueNull= null;
		$rechercheSurValidation = false;
		$rechercheSurValideur = false;

									/************************    CREATION DES JOINTURES   ********************************************************/

        if ($entity_objRechercheBon->getValidationFacturation()) {
			$rechercheSurValidation = true;
			$queryBuilder   ->leftJoin('b.validationFacturation', 'vf');
			if ($entity_objRechercheBon->getValideur()) {
				$rechercheSurValideur = true;
				$queryBuilder   ->leftJoin('vf.user','vfu');
			}
        }


        if ($entity_objRechercheBon->getValidationHoraire()) {
			$rechercheSurValidation = true;
			$queryBuilder   ->leftJoin('b.validationHoraire', 'vh');
			if ($entity_objRechercheBon->getValideur()) {
				$rechercheSurValideur = true;
				$queryBuilder   ->leftJoin('vh.user','vhu');
			}
        }


        if ($entity_objRechercheBon->getValidationSAV()) {
			$rechercheSurValidation = true;
			$queryBuilder   ->leftJoin('b.validationSAV', 'vs');
            if ($entity_objRechercheBon->getValideur()) {
				$rechercheSurValideur = true;
                $queryBuilder   ->leftJoin('vs.user','vsu');
            }
        }


        if ($entity_objRechercheBon->getValidationTechnique()) {
			$rechercheSurValidation = true;
			$queryBuilder   ->leftJoin('b.validationTechnique', 'vt');
            if ($entity_objRechercheBon->getValideur()) {
				$rechercheSurValideur = true;
                $queryBuilder   ->leftJoin('vt.user','vtu');
            }
        }

								/***********************************    PREPARATION DES OBJETS DE CONDITION DE RECHERCHE SUR LA VALIDATION     *********************************************/

        /* Recherche sur les validations de bons (suite)*/
        if ($entity_objRechercheBon->getValidationFacturation()) {
			if ($entity_objRechercheBon->getValideur()) {
            	$qbExprFacture =   $queryBuilder->expr()->andX(
            	                		$queryBuilder->expr()->eq('vf.valide', ':sensValidation'),
            	                		$queryBuilder->expr()->eq('vfu', ':valideur')
            						);
			} else {
				$qbExprFacture =   $queryBuilder->expr()->eq('vf.valide', ':sensValidation');
				// Si on recherche les bons non validés, on recherche alors les bons dont la validation = 0 (cad dé-validés) et le bon dont le champs validation = NULL
				if ($sens_validation === 0){
					$qbExprFactureNull =   $queryBuilder->expr()->isNull('b.validationFacturation');
				}
			}
        }

        if ($entity_objRechercheBon->getValidationHoraire()) {
			if ($entity_objRechercheBon->getValideur()) {
            	$qbExprHoraire =    $queryBuilder->expr()->andX(
            	                		$queryBuilder->expr()->eq('vh.valide', ':sensValidation'),
            	                		$queryBuilder->expr()->eq('vfu', ':valideur')
            						);
            } else {
				$qbExprHoraire =   $queryBuilder->expr()->eq('vh.valide', ':sensValidation');
				if ($sens_validation === 0){
					$qbExprHoraireNull =   $queryBuilder->expr()->isNull('b.validationHoraire');
				}
			}
        }

        if ($entity_objRechercheBon->getValidationSAV()) {
			if ($entity_objRechercheBon->getValideur()) {
            	$qbExprSAV =    	$queryBuilder->expr()->andX(
            	                		$queryBuilder->expr()->eq('vs.valide', ':sensValidation'),
            	                		$queryBuilder->expr()->eq('vsu', ':valideur')
            						);
            } else {
                $qbExprSAV =   $queryBuilder->expr()->eq('vs.valide', ':sensValidation');
				if ($sens_validation === 0){
					$qbExprSAVNull =   $queryBuilder->expr()->isNull('b.validationSAV');
				}
			}
        }

        if ($entity_objRechercheBon->getValidationTechnique()) {
			if ($entity_objRechercheBon->getValideur()) {
            	$qbExprTechnique =  $queryBuilder->expr()->andX(
            	                		$queryBuilder->expr()->eq('vt.valide', ':sensValidation'),
            	                		$queryBuilder->expr()->eq('vtu', ':valideur')
            						);
            } else {
                $qbExprTechnique = $queryBuilder->expr()->eq('vt.valide', ':sensValidation');
				if ($sens_validation === 0){
					$qbExprTechniqueNull =  $queryBuilder->expr()->isNull('b.validationTechnique');
				}
			}
        }

		if ($rechercheSurValidation == true) {
			$queryBuilder->setParameter('sensValidation', $sens_validation);
		}
		if ($rechercheSurValideur == true) {
			 $queryBuilder->setParameter('valideur', $entity_objRechercheBon->getValideur());
		}


								/********************************************************************************/

 		if ($entity_objRechercheBon->getValidationFacturation() || $entity_objRechercheBon->getValidationTechnique() || $entity_objRechercheBon->getValidationSAV() || $entity_objRechercheBon->getValidationHoraire()) {
			if ($entity_objRechercheBon->getSensValidation() === 0){
				$queryBuilder   ->andWhere(
					$queryBuilder->expr()->orX(
						$queryBuilder->expr()->andX($qbExprFacture, $qbExprHoraire, $qbExprSAV, $qbExprTechnique),
						$queryBuilder->expr()->andX($qbExprFactureNull, $qbExprHoraireNull, $qbExprSAVNull, $qbExprTechniqueNull)
					)
				);
			} elseif ($entity_objRechercheBon->getSensValidation() === 1) {
				$queryBuilder   ->andWhere( $queryBuilder->expr()->andX($qbExprFacture, $qbExprHoraire, $qbExprSAV, $qbExprTechnique) );
			} elseif ($entity_objRechercheBon->getSensValidation() === null) {
				$queryBuilder   ->andWhere( $queryBuilder->expr()->orX($qbExprFacture, $qbExprHoraire, $qbExprSAV, $qbExprTechnique) );
			}
        }

        if ($entity_objRechercheBon->getDateMax()) {
            $queryBuilder   ->andWhere($queryBuilder->expr()->between('b.dateSignature', ':dateMin', ':dateMax'))
                            ->setParameter('dateMin', $this->convertirDate($entity_objRechercheBon->getDateMin()))
                            ->setParameter('dateMax', $this->convertirDate($entity_objRechercheBon->getDateMax()));
        } else if ($entity_objRechercheBon->getDateMin()) {
            $queryBuilder   ->andWhere($queryBuilder->expr()->eq('b.dateSignature', ':dateMin'))
                            ->setParameter('dateMin', $this->convertirDate($entity_objRechercheBon->getDateMin()));
        }


        if ($entity_objRechercheBon->getDateMaxInitialisation()) {
            $queryBuilder   ->andWhere($queryBuilder->expr()->between('b.dateInitialisation', ':dateMinInitialisation', ':dateMaxInitialisation'))
                            ->setParameter('dateMinInitialisation', $this->convertirDate($entity_objRechercheBon->getDateMinInitialisation()))
                            ->setParameter('dateMaxInitialisation', $this->convertirDate($entity_objRechercheBon->getDateMaxInitialisation()));
        } else if ($entity_objRechercheBon->getDateMinInitialisation()) {
            $queryBuilder   ->andWhere($queryBuilder->expr()->eq('b.dateInitialisation', ':dateMinInitialisation'))
                            ->setParameter('dateMinInitialisation', $this->convertirDate($entity_objRechercheBon->getDateMinInitialisation()));
        }

        if ($entity_objRechercheBon->getDateMaxIntervention()) {
           	$queryBuilder   ->andWhere(
                              	$queryBuilder->expr()->OrX(
                                    $queryBuilder->expr()->between('b.dateDebutIntervention', ':dateMinIntervention', ':dateMaxIntervention'),
                                    $queryBuilder->expr()->between('b.dateFinIntervention', ':dateMinIntervention', ':dateMaxIntervention'),
									$queryBuilder->expr()->AndX(
										$queryBuilder->expr()->lte('b.dateDebutIntervention', ':dateMinIntervention'),
										$queryBuilder->expr()->gte('b.dateFinIntervention', ':dateMinIntervention')
									)
                                )
                            )
                            ->setParameter('dateMinIntervention', $this->convertirDate($entity_objRechercheBon->getDateMinIntervention()))
                            ->setParameter('dateMaxIntervention', $this->convertirDate($entity_objRechercheBon->getDateMaxIntervention()));
        } else if ($entity_objRechercheBon->getDateMinIntervention()) {
            $queryBuilder   ->andWhere($queryBuilder->expr()->gte('b.dateDebutIntervention', ':dateMinIntervention'))
                            ->setParameter('dateMinIntervention', $this->convertirDate($entity_objRechercheBon->getDateMinIntervention()));
        }



		/*	*********************************************  Si la recherche porte sur un bon saisie, on vérifie qu'un numéro de bon est entré *********************************************/

		if ($entity_objRechercheBon->getSaisie() !== null) {
			if ($entity_objRechercheBon->getSaisie() == false) {
				$queryBuilder   ->andWhere(
					$queryBuilder->expr()->orX(
										$queryBuilder->expr()->isNull('b.numeroBA'),
										$queryBuilder->expr()->like('b.numeroBA', ':empty')
									))
								->setParameter('empty', '');
			} elseif ($entity_objRechercheBon->getSaisie() == true) {
				$queryBuilder   ->andWhere(
        	        $queryBuilder->expr()->andX(
        	                            $queryBuilder->expr()->isNotNull('b.numeroBA'),
										$queryBuilder->expr()->notLike('b.numeroBA', ':empty')
        	                        ))
        	                    ->setParameter('empty', '');
			}
		}

        return $queryBuilder->getQuery()->getResult();
    }




	private function convertirDate($date) {
		// On remplace les caractères - par /
		$date = str_replace('/', '-', $date);
		return date_format(date_create($date), 'Y-m-d');
	}
}
