<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\GaufretteTrait as GaufretteTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 * @ORM\Table(name="media")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Media
{
    use GaufretteTrait;

    /**
     * Id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Title
     *
     * @ORM\Column(length=255, nullable=true)
     */
    protected $title;

    /**
     * Alt
     *
     * @ORM\Column(length=255, nullable=true)
     */
    protected $alt;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $pathMedia;

    protected $fileMedia;

    protected $fileAtDeleteMedia = false;

    protected $base64Media;

    /**
     * Mime Type
     *
     * @ORM\Column(length=255, nullable=true)
     */
    protected $mimetype;


    /**
     * Extension
     *
     * @ORM\Column(length=255, nullable=true)
     */
    protected $extension;

    /**
     * Size
     *
     * @ORM\Column(length=255, nullable=true)
     */
    protected $size;


    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Creation
     *
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * Edit
     *
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

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
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param mixed $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * @return mixed
     */
    public function getPathMedia()
    {
        return $this->pathMedia;
    }

    /**
     * @param mixed $pathMedia
     */
    public function setPathMedia($pathMedia)
    {
        $this->pathMedia = $pathMedia;
    }

    /**
     * @return mixed
     */
    public function getFileMedia()
    {
        return $this->fileMedia;
    }

    /**
     * @param mixed $fileMedia
     */
    public function setFileMedia($fileMedia)
    {
        $this->fileMedia = $fileMedia;
    }

    /**
     * @return boolean
     */
    public function isFileAtDeleteMedia()
    {
        return $this->fileAtDeleteMedia;
    }

    /**
     * @param boolean $fileAtDeleteMedia
     */
    public function setFileAtDeleteMedia($fileAtDeleteMedia)
    {
        $this->fileAtDeleteMedia = $fileAtDeleteMedia;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return mixed
     */
    public function getBase64Media()
    {
        return $this->base64Media;
    }

    /**
     * @param mixed $base64Media
     */
    public function setBase64Media($base64Media)
    {
        $this->base64Media = $base64Media;
    }



    /**
     * To string data
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }

    /**
     * Get public path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getWebPath('Media');
    }

    /**
     * @return mixed
     */
    public function getMimetype()
    {
        if (!$this->mimetype) {
            $this->mimetype = "Undefined";
        }

        return $this->mimetype;
    }

    /**
     * @param mixed $mimetype
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        $exp = explode('/', $this->getPath());
        $exp = explode('_', $exp[count($exp) - 1]);
        return $exp[count($exp) - 1];
    }

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param string $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param string $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }
}
