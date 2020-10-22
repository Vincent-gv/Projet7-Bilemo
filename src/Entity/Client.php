<?php

namespace App\Entity;


use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ORM\Table(name="client")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "show"})
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "show"})
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clients")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getRouteList()
    {
        return [
            'Links'=>[
                'See client (GET)'=>[
                    'href'=>'/client/' . $this->id
                ],
                'Add client (POST)'=>[
                    'href'=>'/client'
                ],
                'Delete client (DEL)'=>[
                    'href'=>'/client/' . $this->id
                ]
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function getRouteShow()
    {
        return [
            'Links'=>[
                'Return to list (GET)'=>[
                    'href'=>'/clients'
                ]
            ]
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
