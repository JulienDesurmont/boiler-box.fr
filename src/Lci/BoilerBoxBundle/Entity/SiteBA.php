<?php
//src/Lci/BoilerBoxBundle/Entity/SiteBA.php

namespace Lci\BoilerBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * SiteBA
 * @ORM\Entity
 * @UniqueEntity("intitule")
 * @ORM\Table(name="siteBA")
 * @ORM\Entity(repositoryClass="Lci\BoilerBoxBundle\Entity\SiteBARepository")
 * @ORM\HasLifecycleCallbacks
*/
class SiteBA
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255)
    */
    protected $intitule;


	/**
	 * Un site peut être la cible de plusieurs bons d'attachements
	 *
	 * @ORM\OneToMany(targetEntity="BonsAttachement", mappedBy="site")
	*/
	protected $bonsAttachement;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bonsAttachement = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     * @return SiteBA
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string 
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Add bonsAttachement
     *
     * @param \Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachement
     * @return SiteBA
     */
    public function addBonsAttachement(\Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachement)
    {
        $this->bonsAttachement[] = $bonsAttachement;

        return $this;
    }

    /**
     * Remove bonsAttachement
     *
     * @param \Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachement
     */
    public function removeBonsAttachement(\Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachement)
    {
        $this->bonsAttachement->removeElement($bonsAttachement);
    }

    /**
     * Get bonsAttachement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBonsAttachement()
    {
        return $this->bonsAttachement;
    }
}
