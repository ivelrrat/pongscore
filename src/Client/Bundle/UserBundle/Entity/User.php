<?php

namespace Client\Bundle\UserBundle\Entity;

use Client\Bundle\GameBundle\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Client\Bundle\GameBundle\Entity\Game;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\AttributeOverrides({ @ORM\AttributeOverride(name="email", column=@ORM\Column(type="string", name="email", length=255, unique=false, nullable=true)), @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(type="string", name="email_canonical", length=255, unique=false, nullable=true))})
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Client\Bundle\GameBundle\Entity\Game", mappedBy="owner")
     */
    private $gamesOwned;

    /**
     * @ORM\ManyToMany(targetEntity="Client\Bundle\GameBundle\Entity\Team", mappedBy="players")
     **/
    private $teams;

    public function __construct()
    {
        parent::__construct();

        $this->gamesOwned = new ArrayCollection();
        $this->teams = new ArrayCollection();
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
        $this->teams[] = $team;

        return $this;
    }

    public function debug($val)
    {
        var_dump($val);
    }
}