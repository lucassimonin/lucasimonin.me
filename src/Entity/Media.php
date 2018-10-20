<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="media")
 * @ORM\HasLifecycleCallbacks
 */
class Media
{
    /**
     * Id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Alt
     *
     * @ORM\Column(length=255, nullable=true)
     * @var string
     */
    protected $alt;
    
    /**
     *
     * @Assert\File(mimeTypes={ "image/*" })
     * @Assert\NotBlank(message="admin.validation.mandatory.label")
     * @var UploadedFile|File
     */
    protected $file;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

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
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getAlt(): ?string
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     */
    public function setAlt(?string $alt): void
    {
        $this->alt = $alt;
    }

    /**
     * @return File|UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File|UploadedFile $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }
}
