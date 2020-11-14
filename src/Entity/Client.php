<?php

namespace App\Entity;


use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 *
 * @Hateoas\Relation(
 *     "self",
 *     href=@Hateoas\Route(
 *          "client_show",
 *          parameters={"id"= "expr(object.getId())"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "client",
 *     embedded = @Hateoas\Embedded         (
 *         {"id"="18","First name"="John","Last name"="Doe","email"="john.doe@mail.com","_links":{"self":{"href": "/client/18"}}}
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href=@Hateoas\Route(
 *          "client_delete",
 *          parameters={"id"= "expr(object.getId())"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "update",
 *     href=@Hateoas\Route(
 *          "client_update",
 *          parameters={"id"= "expr(object.getId())"}
 *     )
 * )
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"list", "show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"list", "show"})
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"list", "show"})
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"show"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clients")
     */
    private $user;

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
