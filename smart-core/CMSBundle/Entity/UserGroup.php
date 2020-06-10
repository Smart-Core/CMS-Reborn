<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\CMSBundle\Model\UserGroupTrait;
use SmartCore\RadBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="users_groups",
 *      indexes={
 *          @ORM\Index(columns={"is_default_folders_granted_read"}),
 *          @ORM\Index(columns={"is_default_folders_granted_write"}),
 *          @ORM\Index(columns={"is_default_nodes_granted_read"}),
 *          @ORM\Index(columns={"is_default_nodes_granted_write"}),
 *          @ORM\Index(columns={"is_default_regions_granted_read"}),
 *          @ORM\Index(columns={"is_default_regions_granted_write"}),
 *          @ORM\Index(columns={"position"}),
 *          @ORM\Index(columns={"title"}),
 *      }
 * )
 */
class UserGroup
{
    use ColumnTrait\Position;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\NameUnique;

    use UserGroupTrait;

    /**
     * Доктрина не создаёт связь м2м, если задать use ColumnTrait\Id;
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected ?array $roles;

    /**
     * @var Permission[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="user_groups", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="cms_permissions_groups_relations")
     * @ORM\OrderBy({"bundle" = "ASC", "position" = "ASC"})
     */
    protected Collection $permissions;

    /**
     * @var Folder[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Folder", mappedBy="groups_granted_read", fetch="EXTRA_LAZY")
     */
    protected Collection $folders_granted_read;

    /**
     * @var Folder[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Folder", mappedBy="groups_granted_write", fetch="EXTRA_LAZY")
     */
    protected Collection $folders_granted_write;

    /**
     * @var Node[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Node", mappedBy="groups_granted_read", fetch="EXTRA_LAZY")
     */
    protected Collection $nodes_granted_read;

    /**
     * @var Node[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Node", mappedBy="groups_granted_write", fetch="EXTRA_LAZY")
     */
    protected Collection $nodes_granted_write;

    /**
     * @var Region[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Region", mappedBy="groups_granted_read", fetch="EXTRA_LAZY")
     */
    protected Collection $regions_granted_read;

    /**
     * @var Region[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Region", mappedBy="groups_granted_write", fetch="EXTRA_LAZY")
     */
    protected Collection $regions_granted_write;

    /**
     * UserGroup constructor.
     *
     * @param string $name
     * @param array  $roles
     */
    public function __construct(string $name, array $roles = [])
    {
        $this->folders_granted_read  = new ArrayCollection();
        $this->folders_granted_write = new ArrayCollection();
        $this->nodes_granted_read    = new ArrayCollection();
        $this->nodes_granted_write   = new ArrayCollection();
        $this->regions_granted_read  = new ArrayCollection();
        $this->regions_granted_write = new ArrayCollection();
        $this->is_default_folders_granted_read  = true;
        $this->is_default_folders_granted_write = true;
        $this->is_default_nodes_granted_read    = true;
        $this->is_default_nodes_granted_write   = true;
        $this->is_default_regions_granted_read  = true;
        $this->is_default_regions_granted_write = true;

        $this->created_at            = new \DateTime();
        $this->permissions           = new ArrayCollection();
        $this->position              = 0;

        $this->name = $name;
        $this->roles = $roles;
    }

    public function __toString(): string
    {
        return (string) $this->getTitle();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Permission $permission
     *
     * @return $this
     */
    public function addPermission(Permission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }

        return $this;
    }

    /**
     * @return Collection|Permission[]
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    /**
     * @param ArrayCollection|Permission[] $permissions
     *
     * @return $this
     */
    public function setPermissions(Collection $permissions): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * @return ArrayCollection|Folder[]
     */
    public function getFoldersGrantedRead(): Collection
    {
        return $this->folders_granted_read;
    }

    /**
     * @param ArrayCollection|Folder[] $folders_granted_read
     *
     * @return $this
     */
    public function setFoldersGrantedRead(Collection $folders_granted_read): self
    {
        $this->folders_granted_read = $folders_granted_read;

        return $this;
    }

    /**
     * @return ArrayCollection|Folder[]
     */
    public function getFoldersGrantedWrite()
    {
        return $this->folders_granted_write;
    }

    /**
     * @param ArrayCollection|Folder[] $folders_granted_write
     *
     * @return $this
     */
    public function setFoldersGrantedWrite($folders_granted_write): self
    {
        $this->folders_granted_write = $folders_granted_write;

        return $this;
    }

    /**
     * @return ArrayCollection|Node[]
     */
    public function getNodesGrantedRead()
    {
        return $this->nodes_granted_read;
    }

    /**
     * @param ArrayCollection|Node[] $nodes_granted_read
     *
     * @return $this
     */
    public function setNodesGrantedRead($nodes_granted_read): self
    {
        $this->nodes_granted_read = $nodes_granted_read;

        return $this;
    }

    /**
     * @return ArrayCollection|Node[]
     */
    public function getNodesGrantedWrite()
    {
        return $this->nodes_granted_write;
    }

    /**
     * @param ArrayCollection|Node[] $nodes_granted_write
     *
     * @return $this
     */
    public function setNodesGrantedWrite($nodes_granted_write): self
    {
        $this->nodes_granted_write = $nodes_granted_write;

        return $this;
    }

    /**
     * @return ArrayCollection|Region[]
     */
    public function getRegionsGrantedRead()
    {
        return $this->regions_granted_read;
    }

    /**
     * @param ArrayCollection|Region[] $regions_granted_read
     *
     * @return $this
     */
    public function setRegionsGrantedRead($regions_granted_read): self
    {
        $this->regions_granted_read = $regions_granted_read;

        return $this;
    }

    /**
     * @return ArrayCollection|Region[]
     */
    public function getRegionsGrantedWrite()
    {
        return $this->regions_granted_write;
    }

    /**
     * @param ArrayCollection|Region[] $regions_granted_write
     *
     * @return $this
     */
    public function setRegionsGrantedWrite($regions_granted_write): self
    {
        $this->regions_granted_write = $regions_granted_write;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefaultRegionsGrantedRead(): bool
    {
        return $this->is_default_regions_granted_read;
    }

    /**
     * @param bool $is_default_regions_granted_read
     *
     * @return $this
     */
    public function setIsDefaultRegionsGrantedRead($is_default_regions_granted_read): self
    {
        $this->is_default_regions_granted_read = $is_default_regions_granted_read;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefaultRegionsGrantedWrite(): bool
    {
        return $this->is_default_regions_granted_write;
    }

    /**
     * @param bool $is_default_regions_granted_write
     *
     * @return $this
     */
    public function setIsDefaultRegionsGrantedWrite($is_default_regions_granted_write): self
    {
        $this->is_default_regions_granted_write = $is_default_regions_granted_write;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function addRole(string $role): self
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }

        return $this;
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    /**
     * @return array|null
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function removeRole(string $role): self
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
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
}
