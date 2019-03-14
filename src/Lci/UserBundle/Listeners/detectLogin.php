<?php
// srv/www/htdocs/Symfony/src/Lci/UserBundle/Listeners/detectLogin.php
namespace Lci\UserBundle\Listeners;

use Symfony\Component\HttpFoundation\Response;

class detectLogin {
    // Sauvegarde de la date de connexion
    public function enregistreConnexion($typeConnexion, $dateConnexion, $token) {
        $fichier_logs = 'logs/connexions.log';
        //echo getcwd();
        $token_fichier_log = fopen($fichier_logs, 'a+');
        // Enregistrement du token dans le fichier
        switch ($typeConnexion) {
            case 'SUCCESS':
                fputs($token_fichier_log,$dateConnexion." Connexion de ".$token->getUser()->getLabel()." [ ".$token->getUser()->getUsername()." ]\n");
                break;
            case 'FAILED':
                fputs($token_fichier_log,$dateConnexion." Tentative de connexion avec l'identifiant [ ".$_POST['_username']." ] et le mot de passe [ ".$_POST['_password']." ]\n");
                break;
            case 'LOGOUT':
                fputs($token_fichier_log,$dateConnexion." Déconnexion de ".$token->getUser()->getLabel()." [ ".$token->getUser()->getUsername()." ]\n");
                break;
        }
        fclose($token_fichier_log);
        return;
    }
}

