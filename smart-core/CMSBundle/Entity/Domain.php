<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cms_domains")
 *
 * @UniqueEntity(fields="name", message="Данный домен занят")
 */
class Domain
{
    use ColumnTrait\Id;
    use ColumnTrait\NameUnique;
    use ColumnTrait\Comment;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\Position;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\User;

    /**
     * For Aliases
     *
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="children")
     * @ORM\JoinColumn(name="parent_pid")
     */
    protected ?Domain $parent;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected bool $is_redirect;

    /**
     * List of aliases
     *
     * @var Domain[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Domain", mappedBy="parent")
     * @ORM\OrderBy({"position" = "ASC", "name" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected ?\DateTimeInterface $paid_till_date;

    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="domains")
     * @ORM\JoinColumn(nullable=true)
     */
    protected ?Language $language;

    public function __construct(?string $name = null)
    {
        if ( ! empty($name)) {
            $this->name = $name;
        }

        $this->children    = new ArrayCollection();
        $this->created_at  = new \DateTime();
        $this->is_enabled  = true;
        $this->is_redirect = false;
        $this->position    = 0;
    }

    /**
     * @return Domain|null
     */
    public function getParent(): ?Domain
    {
        return $this->parent;
    }

    /**
     * @param Domain|null $parent
     *
     * @return $this
     */
    public function setParent(?Domain $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIsRedirect(): bool
    {
        return $this->is_redirect;
    }

    /**
     * @param bool $is_redirect
     *
     * @return $this
     */
    public function setIsRedirect(bool $is_redirect): self
    {
        $this->is_redirect = $is_redirect;

        return $this;
    }

    /**
     * @return Collection|Domain[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Collection|Domain[] $children
     *
     * @return $this
     */
    public function setChildren($children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getPaidTillDate(): ?\DateTimeInterface
    {
        return $this->paid_till_date;
    }

    /**
     * @param \DateTimeInterface|null $paid_till_date
     *
     * @return $this
     */
    public function setPaidTillDate(?\DateTimeInterface $paid_till_date): self
    {
        $this->paid_till_date = $paid_till_date;

        return $this;
    }

    /**
     * @return Language|null
     */
    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @param Language|null $language
     *
     * @return $this
     */
    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }
}
