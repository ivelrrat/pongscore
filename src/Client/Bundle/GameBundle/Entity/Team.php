<?php

namespace Client\Bundle\GameBundle\Entity;

use Client\Bundle\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Team
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="teams")
     **/
    private $game;

    /**
     * @ORM\ManyToMany(targetEntity="Client\Bundle\UserBundle\Entity\User", inversedBy="teams")
     * @ORM\JoinTable(name="users_teams")
     **/
    private $players;

    /**
     * @ORM\OneToMany(targetEntity="Point", mappedBy="team", cascade={"persist"})
     */
    private $points;

    function __construct()
    {
        $this->timestamp = new \DateTime('now');
        $this->players = new ArrayCollection();
        $this->points = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Team
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
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return Team
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
     * @param Game $game
     */
    public function setGame(Game $game)
    {
        $this->game = $game;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @return ArrayCollection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Add Player
     *
     * @param User $user
     * @return Team
     */
    public function addPlayer(User $user)
    {
        $user->addTeam($this);
        $this->players[] = $user;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Add Player
     *
     * @param Point $point
     * @return Team
     */
    public function addPoint(Point $point)
    {
        $point->setTeam($this);
        $this->points[] = $point;

        return $this;
    }

    public function canJoin(User $user)
    {
        return count($this->players) < 2 && !$this->players->contains($user);
    }

    public function hasPlayer(User $user)
    {
        return $this->players->contains($user);
    }
}
