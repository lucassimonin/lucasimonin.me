<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity()
 * @ORM\Table(name="work_translation")
 */
class WorkTranslation implements ContentInterface
{
    use ORMBehaviors\Translatable\Translation;

    protected static $keyCache = 'app.works.';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @var string
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     */
    private $description;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function keyCache(): string
    {
        return static::$keyCache;
    }
}
