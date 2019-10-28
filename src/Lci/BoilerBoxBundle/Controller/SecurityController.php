<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lci\BoilerBoxBundle\Controller;

/*
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Translation\TranslatorInterface;
*/





use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Otp\Otp;
use Otp\GoogleAuthenticator;
use ParagonIE\ConstantTime\Encoding;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
    	$translator = $this->get('translator');
    	// Récupération des informations de la date courante
    	$service_configuration  = $this->container->get('lci_boilerbox.configuration');
    	$tab_date = $service_configuration->maj_date();

        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            //$error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
			$error = "Erreur d'identification";
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            //$error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
			$error = "Erreur d'identification";
        } else {
			// Pas d'erreur d'authentification: On effectue l'authentification par google authenticator
            $error = null;
        }

		/*
        if (! $error instanceof AuthenticationException) {
			$error = "Erreur d'identification";
        }
		*/

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        $csrfToken = $this->has('form.csrf_provider')
            ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;

    	return $this->renderLogin(array(
    	    'last_username' => $lastUsername,
    	    'error' => $translator->trans($error),
    	    'csrf_token' => $csrfToken,
    	    'leJour' => $tab_date['jour'],
    	    'lHeure' => $tab_date['heure'],
    	    'timestamp' => $tab_date['timestamp']
    	));
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        //return $this->render('FOSUserBundle:Security:login.html.twig', $data);
		return $this->render('LciBoilerBoxBundle:Connexion:login.html.twig', $data);
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

}
