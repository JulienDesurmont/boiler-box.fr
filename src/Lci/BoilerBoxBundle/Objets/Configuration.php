<?php 
// src/Lci/BoilerBoxBundle/Objets/Configuration.php
namespace Lci\BoilerBoxBundle\Objets;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configuration
 *
*/

class Configuration {

    /**
     *
	*/
	protected $apiKey;


	public function __construct() {
		$this->apiKey = 'AIzaSyA4ceVB6W6udd67ihnRTeR_Oiip9tY_87s';
		//$this->apiKey = 'AIzaSyDWDE06KALl3FUgCr8FczcO2vzc2baEBKg';
	}


    /**
     * Get apiKey
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     * @return Configuration
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

}
