<?php
//src/Lci/BoilerBoxBundle/Entity/SiteBA.php

namespace Lci\BoilerBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Nom du site manquant")
    */
    protected $intitule;


	/**
	 * Un site peut être la cible de plusieurs bons d'attachements
	 *
	 * @ORM\OneToMany(targetEntity="BonsAttachement", mappedBy="site")
	*/
	protected $bonsAttachement;

	
	/**
	 * @var string
	 * @Assert\Url(message = "L'Url {{ value }} est invalide")
	 *
	 * @ORM\Column(type="string", nullable=true)
	*/
	protected $lienGoogle;


	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255, nullable=true)
	*/
	protected $adresse;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
    */
    protected $contact;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
    */
    protected $emailContact;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
    */
    protected $telContact;



	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	*/
	protected $informationsClient;


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

    /**
     * Set lienGoogle
     *
     * @param string $lienGoogle
     * @return SiteBA
     */
    public function setLienGoogle($lienGoogle)
    {
        $this->lienGoogle = $lienGoogle;

        return $this;
    }

    /**
     * Get lienGoogle
     *
     * @return string
     */
    public function getLienGoogle()
    {
        return $this->lienGoogle;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return SiteBA
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set informationsClient
     *
     * @param string $informationsClient
     * @return SiteBA
     */
    public function setInformationsClient($informationsClient)
    {
        $this->informationsClient = $informationsClient;

        return $this;
    }

    /**
     * Get informationsClient
     *
     * @return string 
     */
    public function getInformationsClient()
    {
        return $this->informationsClient;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return SiteBA
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set emailContact
     *
     * @param string $emailContact
     * @return SiteBA
     */
    public function setEmailContact($emailContact)
    {
        $this->emailContact = $emailContact;

        return $this;
    }

    /**
     * Get emailContact
     *
     * @return string 
     */
    public function getEmailContact()
    {
        return $this->emailContact;
    }

    /**
     * Set telContact
     *
     * @param string $telContact
     * @return SiteBA
     */
    public function setTelContact($telContact)
    {
        $this->telContact = $telContact;

        return $this;
    }

    /**
     * Get telContact
     *
     * @return string 
     */
    public function getTelContact()
    {
        return $this->telContact;
    }
}
