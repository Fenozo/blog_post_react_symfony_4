<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\ResetPasswordAction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *                 "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *                  "normalization_context"={
 *                      "groups"={"get"}
 *                  }
 *          },
 *          "put"={
 *                  "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object == user",
 *                  "denormalization_context"={
 *                      "groups"={"put"}
 *                  },
 *                  "normalization_context"={
 *                      "groups"={"get"}
 *                  }
 *          },
 *          "put-reset-password"={
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object == user",
 *              "method"="PUT",
 *              "path"="/users/{id}/reset-password",
 *              "controller"=ResetPasswordAction::class,
 *              "denormalization_context"={
 *                  "groups"={"put-reset-password"}
 *              }
 *          }
 *      },
 *     collectionOperations={
 *          
 *          "post"={
 *              "denormalization_context"={
 *                  "groups"={"post"}
 *              },
 *              "normalisation_context"={
 *                  "groups"={"get"}
 *              }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    const ROLE_COMMENTATOR = 'ROLE_COMMENTATOR';
    const ROLE_WRITER = 'ROLE_WRITER';
    const ROLE_EDITOR = 'ROLE_EDITOR';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';

    const DEFAULT_ROLES = [self::ROLE_COMMENTATOR];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get","get-post-with-author", "get-comment-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Length(min=6, max=255, groups={"post"})
     * @Groups({"get", "post", "get-comment-with-author", "get-blog-post-with-author"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Password must be seven characters long and contain at least on digit, one upper case letter and one lower case letter",
     *     groups={"post"}
     * )
     */
    private $password;

    /**
     * @Groups({"post"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Expression(
     *     "this.getPassword() === this.getRetypedPassword()",
     *      message="Passwords does not match",
     *     groups={"post"}
     * )
     */
    private $retypedPassword;


    /**
     * @Groups({"put-reset-password"})
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Password must be seven characters long and contain at least on digit, one upper case letter and one lower case letter",
     *     groups={"put-reset-password"}
     * )
     */
    private $newPassword;


    /**
     * @Groups({"put-reset-password"})
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @Assert\Expression(
     *     "this.getNewPassword() === this.getNewRetypedPassword()",
     *      message="Passwords does not match",
     *     groups={"put-reset-password"}
     * )
     */
    private $newRetypedPassword;
    /**
     * @Groups({"put-reset-password"})
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @UserPassword(groups={"put-reset-password"})
     */
    private $oldPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get", "post", "put", "get-comment-with-author", "get-blog-post-with-author"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Length(min="5", max="255", groups={"post", "put"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Email(groups={"post", "put"})
     * @Assert\Length(max="255", groups={"post", "put"})
     * @Groups({"post", "put", "get-admin", "get-owner",  "get-comment-with-author"})
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPost", mappedBy="author")
     * @Groups({"get"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     * @Groups({"get"})
     */
    private $comments;

    /**
     * @ORM\Column(type="simple_array", length=200)
     * @Groups({"get-admin", "get-owner"})
     */
    private $roles;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $passwordChangeDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $confirmationToken;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->roles = self::DEFAULT_ROLES;
        $this->enabled = false;
        $this->confirmationToken = null;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection
     */
    public function getPosts():Collection
    {
        return $this->posts;
    }

    /**
     * @return Collection
     */
    public function getComments():Collection
    {
        return $this->comments;
    }


    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        RETURN NULL;
    }

    /**
     * @return mixed
     */
    public function eraseCredentials()
    {

    }

    /**
     * @return mixed
     */
    public function getRetypedPassword()
    {
        return $this->retypedPassword;
    }

    /**
     * @param mixed $retypedPassword
     * @return User
     */
    public function setRetypedPassword($retypedPassword)
    {
        $this->retypedPassword = $retypedPassword;
        return $this;
    }


    public function getNewPassword():?string
    {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
        return $this;
    }
    public function getNewRetypedPassword():?string
    {
        return $this->newRetypedPassword;
    }
    public function setNewRetypedPassword($newRetypedPassword)
    {
        $this->newRetypedPassword = $newRetypedPassword;
        return $this;
    }

    public function getOldPassword():?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }


    public function getPasswordChangeDate()
    {
        return $this->passwordChangeDate;
    }

    public function setPasswordChangeDate($passwordChangeDate)
    {
        $this->passwordChangeDate = $passwordChangeDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
