<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cms_regions",
 *      indexes={
 *          @ORM\Index(columns={"position"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="region_name_in_site", columns={"name", "site_id"}),
 *      }
 * )
 * @UniqueEntity(fields={"name", "site"}, message="Регион с таким именем уже используется")
 * @ORM\HasLifecycleCallbacks
 */
class Region
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Description;
    use ColumnTrait\Position;
    use ColumnTrait\NameNotBlank;
    use ColumnTrait\User;

    /**
     * @ORM\Column(type="array")
     */
    protected array $permissions_cache;

    /**
     * @var Folder[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Folder", inversedBy="regions", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="cms_regions_inherit")
     */
    protected Collection $folders;

    /**
     * @var UserGroup[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="regions_granted_read", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="cms_permissions_regions_read")
     * @ORM\OrderBy({"position" = "ASC", "title" = "ASC"})
     */
    protected Collection $groups_granted_read;

    /**
     * @var UserGroup[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="regions_granted_write", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="cms_permissions_regions_write")
     * @ORM\OrderBy({"position" = "ASC", "title" = "ASC"})
     */
    protected Collection $groups_granted_write;

    /**
     * @ORM\ManyToOne(targetEntity="Site")
     * @ORM\JoinColumn(nullable=false)
     */
    protected Site $site;

    /**
     * Region constructor.
     *
     * @param null|string $name
     * @param null|string $description
     * @param Site|null   $site
     */
    public function __construct(?string $name = null, ?string $description = null, ?Site $site = null)
    {
        $this->groups_granted_read  = new ArrayCollection();
        $this->groups_granted_write = new ArrayCollection();
        $this->permissions_cache    = [];

        $this->created_at   = new \DateTime();
        $this->folders      = new ArrayCollection();
        $this->description  = $description;
        $this->name         = $name;
        $this->position     = 0;
        $this->site         = $site;
    }

    public function __toString(): string
    {
        $descr = $this->getDescription();

        return empty($descr) ? $this->getName() : $descr.' ('.$this->getName().')';
    }

    /**
     * @ORM\PreFlush()
     */
    public function updatePermissionsCache(): void
    {
        $this->permissions_cache = [];

        foreach ($this->getGroupsGrantedRead() as $group) {
            $this->permissions_cache['read'][$group->getId()] = $group->getName();
        }

        foreach ($this->getGroupsGrantedWrite() as $group) {
            $this->permissions_cache['write'][$group->getId()] = $group->getName();
        }
    }

    public function getPermissionsCache(string $permission = null): array
    {
        if (!empty($permission)) {
            if (isset($this->permissions_cache[$permission])) {
                return $this->permissions_cache[$permission];
            } else {
                return [];
            }
        }

        if (empty($this->permissions_cache)) {
            $this->permissions_cache = [];
        }

        return $this->permissions_cache;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name): self
    {
        if ('content' !== $this->name) {
            $this->name = $name;
        }

        return $this;
    }

    public function addFolder(Folder $folder): self
    {
        $this->folders->add($folder);

        return $this;
    }

    /**
     * @return Collection|Folder[]
     */
    public function getFolders(): Collection
    {
        return $this->folders;
    }

    /**
     * @param Collection|Folder[] $folders
     *
     * @return $this
     */
    public function setFolders(Collection $folders): self
    {
        $this->folders = $folders;

        return $this;
    }

    /**
     * @param UserGroup $userGroup
     *
     * @return Region
     */
    public function addGroupGrantedRead(UserGroup $userGroup): self
    {
        if (!$this->groups_granted_read->contains($userGroup)) {
            $this->groups_granted_read->add($userGroup);
        }

        return $this;
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getGroupsGrantedRead(): Collection
    {
        return $this->groups_granted_read;
    }

    /**
     * @param Collection|UserGroup[] $groups_granted_read
     *
     * @return $this
     */
    public function setGroupsGrantedRead($groups_granted_read)
    {
        $this->groups_granted_read = $groups_granted_read;

        return $this;
    }

    /**
     * @param UserGroup $userGroup
     *
     * @return Region
     */
    public function addGroupGrantedWrite(UserGroup $userGroup): Region
    {
        if (!$this->groups_granted_write->contains($userGroup)) {
            $this->groups_granted_write->add($userGroup);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|UserGroup[]
     */
    public function getGroupsGrantedWrite(): Collection
    {
        return $this->groups_granted_write;
    }

    /**
     * @param ArrayCollection|UserGroup[] $groups_granted_write
     *
     * @return $this
     */
    public function setGroupsGrantedWrite($groups_granted_write)
    {
        $this->groups_granted_write = $groups_granted_write;

        return $this;
    }

    /**
     * @param array $permissions_cache
     *
     * @return $this
     */
    public function setPermissionsCache($permissions_cache)
    {
        $this->permissions_cache = $permissions_cache;

        return $this;
    }

    /**
     * @return Site
     */
    public function getSite(): Site
    {
        return $this->site;
    }

    /**
     * @param Site $site
     *
     * @return $this
     */
    public function setSite(Site $site): Region
    {
        $this->site = $site;

        return $this;
    }
}
