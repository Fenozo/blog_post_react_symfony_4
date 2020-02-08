<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

//
// /?author=/api/users/29
// /?published[after]=2018-02-12
// /?published[after]=2018-07-12&published[before]=2019-07-30
// /?id[gt]=400 | id[gt] - greater than
// /?id[gt]=360&id[lt]=401 | id[lt] - less than
// /?id[gte]=401 | id[gte] - greater than or equal
// /?id[lte]=401 | id[lte] - less than or equal
// /?order[id]=desc
// /?order[id]=asc
// /?author.name=John Doe
// /?properties[]=title | ?properties[]=id | ?properties[]=content

//

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostRepository")
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *         "id":"exact",
 *          "title":"partial",
 *          "content":"partial",
 *          "author":"exact",
 *          "author.name":"partial"
 *      }
 * )
 * @ApiFilter(
 *     DateFilter::class,
 *     properties={
 *         "published"
 *     }
 * )
 * @ApiFilter(
 *     RangeFilter::class,
 *     properties={"id","title"}
 * )
 * @ApiFilter(
 *     OrderFilter::class,
 *     properties={
 *         "id",
 *         "published",
 *         "title"
 *     }
 * )
 * @ApiFilter(
 *     PropertyFilter::class, arguments={
 *         "parameterName": "properties",
 *         "overrideDefaultProperties": false,
 *         "whitelist":{"id", "author", "slug", "title", "content"}
 *     }
 * )
 * @ApiResource(
 *     attributes={"order"={"id":"DESC"}},
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"get-blog-post-with-author"}
 *              }
 *           },
 *           "put"={
 *              "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_WRITER') and object.getAuthor() == user)"
 *           }
 *      },
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "access_control"="is_granted('ROLE_WRITER')"
 *          }
 *      },
 *     denormalizationContext={
 *          "groups"={"post"}
 *     }
 * )
 */
class BlogPost implements AuthoredEntityInterface, PubishedDateEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-blog-post-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post", "get-blog-post-with-author"})
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post",  "get-blog-post-with-author"})
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"get-blog-post-with-author"})
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post", "get-blog-post-with-author"})
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Groups({"post"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="blogPost")
     * @ApiSubresource()
     * @Groups({"get-blog-post-with-author"})
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt = null ):? self
    {
        $this->createdAt = ($createdAt == null)? new \DateTime('NOW'): $createdAt;
        //$this->createdAt = ($this->createdAt == null) ? new \DateTime(): $this->createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor():? User
    {
        return $this->author;
    }

    /**
     * @param UserInterface $author
     * @return AuthoredEntityInterface
     */
    public function setAuthor(UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug():?string
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return BlogPost
     */
    public function setSlug($slug=null)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     * @return BlogPost
     */
    public function setPublished(\DateTimeInterface $published): PubishedDateEntityInterface
    {
        $this->published = $published;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getComments():Collection
    {
        return $this->comments;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
