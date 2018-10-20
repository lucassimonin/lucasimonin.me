<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable, TwoFactorInterface
{
    use TimestampableTrait;
    const PATTERN_EMAIL = '/^(?:(?=.*[a-z])(?:(?=.*[A-Z])(?=.*[\d\W])|(?=.*\W)(?=.*\d))|(?=.*\W)(?=.*[A-Z])(?=.*\d)).{8,}$/';
    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_TRANSLATOR = 'ROLE_TRANSLATOR';
    const ROLE_EXCLUDE_TRANSLATOR = ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN'];

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="googleAuthenticatorSecret", type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;

    /**
     * Firstname
     * @var string
     * @ORM\Column(name="first_name", length=255, nullable=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Length(min="2", max="255", minMessage="admin.validation.too_short.label", maxMessage="admin.validation.too_long.label")
     */
    private $firstName;

    /**
     * Lastname
     * @var string
     * @ORM\Column(name="last_name", length=255, nullable=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Length(min="2", max="255", minMessage="admin.validation.too_short.label", maxMessage="admin.validation.too_long.label")
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     */
    private $username;

    /**
     * Slug
     *
     * @Gedmo\Slug(fields={"username"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Email(message="admin.validation.email.label")
     */
    private $email;

    /**
     * @var array
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @var boolean
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var string
     * @ORM\Column(name="confirmation_token", type="string", nullable=true)
     */
    private $confirmationToken;

    /**
     * @var string
     *
     */
    private $plainPassword;

    /**
     * @var string $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(type="string", nullable=true)
     */
    private $createdBy;

    /**
     * @var string $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(type="string", nullable=true)
     */
    private $updatedBy;

    public function __construct($enabled = false)
    {
        $this->enabled = $enabled;
        $this->roles = [];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getUsername();
    }

    /**
     * @param $role
     *
     * @return $this
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param $role
     *
     * @return $this
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return $this->googleAuthenticatorSecret ? true : false;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->username  == null ? '' : $this->username;
    }

    public function getGoogleAuthenticatorSecret(): string
    {
        return $this->googleAuthenticatorSecret == null ? '' : $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }

    /**
     * @return mixed
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
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
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    /**
     * @param string $createdBy
     */
    public function setCreatedBy(string $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return string
     */
    public function getUpdatedBy(): string
    {
        return $this->updatedBy;
    }

    /**
     * @param string $updatedBy
     */
    public function setUpdatedBy(string $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param string $confirmationToken
     */
    public function setConfirmationToken(?string $confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->email
        ));
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->email
            ) = unserialize($serialized);
    }
}
