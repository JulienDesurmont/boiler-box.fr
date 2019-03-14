<?php
$monFichier = "/srv/www/htdocs/Symfony/web/logs/connexions.log";

if (file_exists($monFichier ))
{
	// Ouverture du fichier
	$file = fopen($monFichier , "r");
	echo "<table>";
	echo "<tr><th style='width:200px;'>Horaire</th><th style='width:200px;'>Information</th><th style='width:300px;'>Utilisateur</th></tr>";
	while($ligne = fgets($file))
	{
		$pattern_tentative = "#Tentative#";
		if (preg_match($pattern_tentative, $ligne, $tab_retour)) {
			$pattern = "#^([^A-Z]+) Tentative de connexion avec l'identifiant (.+)$#";
			if (preg_match($pattern, $ligne, $tab_retour)) {
				echo "<tr><td>Le ".utf8_decode($tab_retour[1])."</td><td>Tentative de connexion</td><td>".ucfirst(utf8_decode($tab_retour[2]))."</td></tr>";
			}
		} else {
			$pattern = "#^([^A-Z]+)([A-Z][^A-Z]+) de (.+)$#";
			if (preg_match($pattern, $ligne, $tab_retour)) {
				echo "<tr><td>Le ".utf8_decode($tab_retour[1])."</td><td>".utf8_decode($tab_retour[2])."</td><td>".ucfirst(utf8_decode($tab_retour[3]))."</td></tr>";
			}
		}
	}
	echo "</table>";
}
