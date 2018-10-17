<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity()
 * @ORM\Table(name="person_translation")
 */
class PersonTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @ORM\Column(name="meta_title", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @var string
     */
    protected $metaTitle;

    /**
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @var string
     */
    protected $metaDescription;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     */
    private $work;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     */
    private $degree;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     */
    private $biography;

    /**
     * @return string
     */
    public function getWork(): ?string
    {
        return $this->work;
    }

    /**
     * @param string $work
     */
    public function setWork(string $work): void
    {
        $this->work = $work;
    }

    /**
     * @return string
     */
    public function getDegree(): ?string
    {
        return $this->degree;
    }

    /**
     * @param string $degree
     */
    public function setDegree(string $degree): void
    {
        $this->degree = $degree;
    }

    /**
     * @return string
     */
    public function getBiography(): ?string
    {
        return $this->biography;
    }

    /**
     * @param string $biography
     */
    public function setBiography(string $biography): void
    {
        $this->biography = $biography;
    }

    /**
     * @return string
     */
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    /**
     * @param string $metaTitle
     */
    public function setMetaTitle(string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * @return string
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription(string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }
}
