<?php

namespace Client\Bundle\GameBundle\Entity;

use Client\Bundle\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Client\Bundle\GameBundle\Entity\GameRepository")
 */
class Game
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Client\Bundle\UserBundle\Entity\User", inversedBy="gamesOwned")
     **/
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="game")
     */
    private $teams;

    /**
     * @var integer
     *
     * @ORM\Column(name="numberOfPlayers", type="integer")
     */
    private $numberOfPlayers;

    function __construct()
    {
        $this->timestamp = new \DateTime('now');
        $this->teams = new ArrayCollection();
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
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return Game
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Set numberOfPlayers
     *
     * @param integer $numberOfPlayers
     * @return Team
     */
    public function setNumberOfPlayers($numberOfPlayers)
    {
        $this->numberOfPlayers = $numberOfPlayers;

        return $this;
    }

    /**
     * Get numberOfPlayers
     *
     * @return integer
     */
    public function getNumberOfPlayers()
    {
        return $this->numberOfPlayers;
    }

    /**
     * @return ArrayCollection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Add Team
     *
     * @param Team $team
     * @return Game
     */
    public function addTeam(Team $team)
    {
        $team->setGame($this);
        $this->teams[] = $team;

        return $this;
    }
}
