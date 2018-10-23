<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContentRepository")
 * @ORM\Table(name="work")
 */
class Work
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="picture_id", referencedColumnName="id", onDelete="SET NULL")
     * @var Media
     */
    private $picture;

    /**
     * @var string
     * @ORM\Column(name="city_url", type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Url(message="admin.validation.mandatory.label")
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     */
    private $tags;

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
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getTags(): ?string
    {
        return $this->tags;
    }

    /**
     * @param string $tags
     */
    public function setTags(string $tags): void
    {
        $this->tags = $tags;
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
        return $this->__call('name', array());
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->__call('name', array());
    }
}
