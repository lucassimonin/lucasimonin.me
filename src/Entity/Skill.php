<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContentRepository")
 * @ORM\Table(name="skill")
 */
class Skill implements ContentInterface
{
    use ORMBehaviors\Translatable\Translatable;
    const SKILL_TYPE_SKILL = 'skill';
    const SKILL_TYPE_LANGUAGE = 'language';
    const SKILL_TYPE_TOOLS = 'tools';
    protected static $keyCache = 'app.skills.';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(length=255, nullable=true)
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     */
    private $type;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @Assert\Length(min="1", max="5", minMessage="admin.validation.mandatory.label", maxMessage="admin.validation.mandatory.label")
     */
    private $note;

    /**
     * Experience constructor.
     */
    public function __construct()
    {
        $this->type = self::SKILL_TYPE_SKILL;
        $this->note = 0;
    }

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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getNote(): int
    {
        return $this->note;
    }

    /**
     * @param int $note
     */
    public function setNote(int $note): void
    {
        $this->note = $note;
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
     * @return string|null
     */
    public function getName()
    {
        return $this->__call('name', array());
    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->__call('name', array());
    }

    public function keyCache(): string
    {
        return static::keyCache() . $this->getType() . '.';
    }
}
