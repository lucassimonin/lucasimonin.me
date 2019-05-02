<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity()
 * @ORM\Table(name="skill_translation")
 */
class SkillTranslation implements ContentInterface
{
    use ORMBehaviors\Translatable\Translation;

    protected static $keyCache = 'app.skills.';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @var string
     */
    protected $name;

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

    public function keyCache(): string
    {
        return static::$keyCache;
    }
}
