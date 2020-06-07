<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use SmartCore\CMSBundle\Entity\UserGroup;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * ORM\Table("users",
 *      indexes={
 *          ORM\Index(columns={"created_at"}),
 *          ORM\Index(columns={"last_login"}),
 *          ORM\Index(columns={"is_enabled"}),
 *      },
 * )
 * UniqueEntity(
 *     fields="username_canonical",
 *     errorPath="username",
 *     message="Username is already exists"
 * )
 * UniqueEntity(
 *     fields="email_canonical",
 *     errorPath="email",
 *     message="Email is already exists"
 * )
 */
abstract class UserModel implements UserInterface
{
    use ColumnTrait\EmailUniqueNotNull;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\IsEnabled;

    /**
     * @ORM\Column(type="string", length=40, nullable=false)
     * @Assert\Length(min = 3, minMessage = "Username length must be at least {{ limit }} characters long")
     * @Assert\NotNull(message="This value is not valid.")
     */
    protected string $username;

    /**
     * @ORM\Column(type="string", length=40, unique=true, nullable=false)
     */
    protected string $username_canonical;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected string $email_canonical;

    /**
     * @ORM\Column(type="string", length=190)
     * @Assert\Length(min = 6, minMessage = "Password length must be at least {{ limit }} characters long")
     */
    protected string $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     */
    protected ?string $plain_password;

    /**
     * @ORM\Column(type="array")
     */
    protected array $roles;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected \DateTimeInterface $last_login;

    /**
     * @var UserGroup[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="SmartCore\CMSBundle\Entity\UserGroup")
     * @ORM\JoinTable(name="users_groups_relations")
     */
    protected Collection $groups;

    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->is_enabled   = false;
        $this->email        = '';
        $this->groups       = new ArrayCollection();
        $this->password     = '';
        $this->roles        = [];
        $this->username     = '';
    }

    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->is_enabled,
            $this->email,
            $this->email_canonical,
            $this->username,
            $this->username_canonical,
            $this->password
        ]);
    }

    public function unserialize(string $serialized): void
    {
        [
            $this->id,
            $this->is_enabled,
            $this->email,
            $this->email_canonical,
            $this->username,
            $this->username_canonical,
            $this->password
        ] = unserialize($serialized, ['allowed_classes' => false]);
    }

    static public function canonicalize(string $string): ?string
    {
        if (null === $string) {
            return null;
        }

        $encoding = mb_detect_encoding($string);
        $result = $encoding
            ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
            : mb_convert_case($string, MB_CASE_LOWER);

        return $result;
    }

    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // if you had a plainPassword property, you'd nullify it here
        $this->plain_password = null;
    }

    /**
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('ROLE_SUPER_ADMIN');
    }

    /**
     * @param bool $boolean
     *
     * @return $this
     */
    public function setSuperAdmin(bool $boolean): self
    {
        if (true === $boolean) {
            $this->addRole('ROLE_SUPER_ADMIN');
        } else {
            $this->removeRole('ROLE_SUPER_ADMIN');
        }

        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = trim($email);
        $this->email_canonical = self::canonicalize(trim($email));

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailCanonical(): string
    {
        return $this->email_canonical;
    }

    /**
     * @param string $email_canonical
     *
     * @return $this
     */
    public function setEmailCanonical(string $email_canonical): self
    {
        $this->email_canonical = $email_canonical;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = trim($username);
        $this->username_canonical = self::canonicalize($this->username);

        return $this;
    }

    /**
     * @return string
     */
    public function getUsernameCanonical(): string
    {
        return $this->username_canonical;
    }

    /**
     * @param string $username_canonical
     *
     * @return $this
     */
    public function setUsernameCanonical(string $username_canonical): self
    {
        $this->username_canonical = $username_canonical;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }

    /**
     * @param string|null $plain_password
     *
     * @return $this
     */
    public function setPlainPassword(?string $plain_password): self
    {
        $this->plain_password = $plain_password;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function addRole(string $role): self
    {
        $role = strtoupper($role);
        if ($role === 'ROLE_USER') {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
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
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     *
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
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
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->last_login;
    }

    /**
     * @param \DateTime|null $last_login
     *
     * @return $this
     */
    public function setLastLogin(?\DateTimeInterface $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }

    /**
     * @return array
     */
    public function getGroupNames(): array
    {
        $names = [];
        foreach ($this->getGroups() as $group) {
            $names[$group->getId()] = $group->getName();
        }

        return $names;
    }

    /**
     * @return UserGroup[]|ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups ?: $this->groups = new ArrayCollection();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasGroup(string $name): bool
    {
        return in_array($name, $this->getGroupNames());
    }

    /**
     * @param UserGroup $group
     *
     * @return $this
     */
    public function addGroup(UserGroup $group): self
    {
        if (!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }

        return $this;
    }

    /**
     * @param UserGroup $group
     *
     * @return $this
     */
    public function removeGroup(UserGroup $group): self
    {
        if ($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }

        return $this;
    }
}
