<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\GaufretteTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity()
 * @ORM\Table(name="person")
 */
class Person
{
    use GaufretteTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="first_name", length=255, nullable=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Length(min="2", max="255", minMessage="admin.validation.too_short.label", maxMessage="admin.validation.too_long.label")
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", length=255, nullable=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Length(min="2", max="255", minMessage="admin.validation.too_short.label", maxMessage="admin.validation.too_long.label")
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media", cascade={"all"})
     * @ORM\JoinColumn(name="picture_id", referencedColumnName="id", onDelete="SET NULL")
     * @var Media
     */
    private $picture;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(name="city_url", type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Url(message="admin.validation.mandatory.label")
     */
    private $cityUrl;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Url(message="admin.validation.mandatory.label")
     */
    private $twitter;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Url(message="admin.validation.mandatory.label")
     */
    private $linkedin;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Url(message="admin.validation.mandatory.label")
     */
    private $github;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Url(message="admin.validation.mandatory.label")
     */
    private $instagram;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Email(message="admin.validation.mandatory.label")
     */
    private $mail;

    /**
     *
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
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
     * @return string
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
     * @return Media
     */
    public function getPicture(): ?Media
    {
        return $this->picture;
    }

    /**
     * @param Media $picture
     */
    public function setPicture(Media $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     */
    public function setBirthday(\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCityUrl(): ?string
    {
        return $this->cityUrl;
    }

    /**
     * @param string $cityUrl
     */
    public function setCityUrl(string $cityUrl): void
    {
        $this->cityUrl = $cityUrl;
    }

    /**
     * @return string
     */
    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    /**
     * @param string $twitter
     */
    public function setTwitter(string $twitter): void
    {
        $this->twitter = $twitter;
    }

    /**
     * @return string
     */
    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    /**
     * @param string $linkedin
     */
    public function setLinkedin(string $linkedin): void
    {
        $this->linkedin = $linkedin;
    }

    /**
     * @return string
     */
    public function getGithub(): ?string
    {
        return $this->github;
    }

    /**
     * @param string $github
     */
    public function setGithub(string $github): void
    {
        $this->github = $github;
    }

    /**
     * @return string
     */
    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    /**
     * @param string $instagram
     */
    public function setInstagram(string $instagram): void
    {
        $this->instagram = $instagram;
    }

    /**
     * @return string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (!method_exists(self::getTranslationEntityClass(), $method)) {
            $method = 'get'.ucfirst($method);
        }

        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->__call('firstName', array());
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->__call('firstName', array());
    }

}
