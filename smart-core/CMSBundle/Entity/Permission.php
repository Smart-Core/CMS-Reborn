<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cms_permissions",
 *      indexes={
 *          @ORM\Index(columns={"position"}),
 *          @ORM\Index(columns={"default_value"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"bundle", "action"}),
 *      }
 * )
 */
class Permission
{
    use ColumnTrait\Id;
    use ColumnTrait\Position;
    use ColumnTrait\CreatedAt;

    /**
     * @ORM\Column(type="string", length=80, nullable=false)
     * @Assert\NotBlank()
     */
    protected string $bundle;

    /**
     * @ORM\Column(type="string", length=80, nullable=false)
     * @Assert\NotBlank()
     */
    protected string $action;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected bool $default_value;

    /**
     * @ORM\Column(type="array")
     */
    protected array $roles;

    /**
     * @var UserGroup[]
     *
     * @ORM\ManyToMany(targetEntity="UserGroup", mappedBy="permissions", fetch="EXTRA_LAZY")
     */
    protected Collection $user_groups;

    public function __construct()
    {
        $this->default_value = false;
        $this->roles         = [];
        $this->created_at    = new \DateTime();
        $this->user_groups   = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getBundle().':'.$this->getAction();
    }

    /**
     * @return string
     */
    public function getBundle(): string
    {
        return $this->bundle;
    }

    /**
     * @param string $bundle
     *
     * @return $this
     */
    public function setBundle(string $bundle): self
    {
        $this->bundle = $bundle;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     *
     * @return $this
     */
    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefaultValue(): bool
    {
        return $this->default_value;
    }

    /**
     * @param bool $default_value
     *
     * @return $this
     */
    public function setDefaultValue(bool $default_value): self
    {
        $this->default_value = $default_value;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return UserGroup[]
     */
    public function getUserGroups(): array
    {
        return $this->user_groups;
    }

    /**
     * @param UserGroup[] $user_groups
     *
     * @return $this
     */
    public function setUserGroups(array $user_groups): self
    {
        $this->user_groups = $user_groups;

        return $this;
    }
}
