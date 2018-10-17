<?php

namespace App\Entity\Traits;

use Cocur\Slugify\Slugify;

trait GaufretteTrait
{
    public $firstPreUpload = false;

    // Attribute for file system gaufrette
    public $fileSystem = null;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploads()
    {
        # Hack to listener passage
        if (!$this->firstPreUpload) {
            $this->firstPreUpload = true;
            return;
        }

        $attributes = $this->getFilesAttributes();
        if (count($attributes) <= 0) {
            return;
        }

        foreach ($attributes as $attribute) {
            $file = 'file'.$attribute;
            $fileAtDelete = 'fileAtDelete'.$attribute;
            $path = 'path'.$attribute;

            if ((method_exists($this, 'get'.ucfirst($fileAtDelete)) or method_exists($this, 'is'.ucfirst($fileAtDelete))) && $this->$fileAtDelete) {
                $this->removeOneUpload($attribute);
            }

            if (null !== $this->$file) {
                $this->removeOneUpload($attribute);
                $slugify = new Slugify();
                $this->$path = $slugify->slugify($this->getClassName()).'/'.$slugify->slugify($attribute).'/'.uniqid().'_'.$slugify->slugify($this->$file->getClientOriginalName()).'.'.$this->$file->guessExtension();
            }
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploads()
    {
        $attributes = $this->getFilesAttributes();
        if (count($attributes) <= 0) {
            return;
        }

        foreach ($attributes as $attribute) {
            $file = 'file'.$attribute;
            $path = 'path'.$attribute;

            if (null === $this->$file) {
                continue;
            }

            if (isset($this->$file)) {
                if (method_exists($this->getAdapter(), 'setMetadata')) {
                    $this->getAdapter()->setMetadata($this->$path, array('contentType' => $this->$file->getClientMimeType()));
                }
                $this->getAdapter()->write($this->$path, file_get_contents($this->$file->getPathname()));
            }

            unset($this->$file);
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUploads()
    {
        $attributes = $this->getFilesAttributes();
        if (count($attributes) <= 0) {
            return;
        }

        foreach ($attributes as $attribute) {
            $this->removeOneUpload($attribute);
        }
    }

    /**
     * Get web path
     *
     * @param $attribute
     * @return null
     */
    public function getWebPath($attribute)
    {
        $attribute = ucfirst(strtolower($attribute));
        $path = 'path'.$attribute;

        // For test fixtures pictures
        if (stripos($this->$path, 'test/') !== false) {
            return  $this->$path;
        }

        // For AWS
        if (method_exists($this->getAdapter(), 'getUrl')) {
            return null === $this->$path ? null : $this->getAdapter()->getUrl($this->$path);
        }

        // For Local
        if ($this->getAdapter()->exists($this->$path)) {
            return null === $this->$path ? null : 'uploads/'.$this->$path;
        }

        return null;
    }

    /**
     * Get files attributes
     */
    public function getFilesAttributes()
    {
        $returns = array();
        $attributes = array_keys(get_object_vars($this));

        foreach ($attributes as $attribute) {
            if ($attribute == 'fileSystem') {
                continue;
            }

            $str = strstr($attribute, 'file');
            $strAtDelete = strstr($attribute, 'fileAtDelete');
            if ($str !== false && $strAtDelete === false) {
                $str = str_replace('file', '', $str);
                $returns[] = $str;
            }
        }

        return $returns;
    }

    /**
     * Remove one upload on gaufrette by attribute
     *
     * @param $attribute
     */
    public function removeOneUpload($attribute)
    {
        $attribute = ucfirst(strtolower($attribute));
        $path = 'path'.$attribute;

        if ($this->$path != null && $this->getAdapter()->exists($this->$path)) {
            $this->getAdapter()->delete($this->$path);
        }

        $this->$path = null;
    }

    /**
     * Get class name
     *
     * @return mixed
     */
    public function getClassName()
    {
        $name = get_class($this);
        $name = explode('\\', $name);
        return $name[count($name) - 1];
    }
    /**
     * Set File System
     *
     * @param $fileSystem
     */
    public function setFileSystem($fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * Get Adapter
     *
     * @return mixed
     */
    public function getAdapter()
    {
        return $this->fileSystem->getAdapter();
    }

    /**
     * @return boolean
     */
    public function isFirstPreUpload()
    {
        return $this->firstPreUpload;
    }

    /**
     * @param boolean $firstPreUpload
     */
    public function setFirstPreUpload($firstPreUpload)
    {
        $this->firstPreUpload = $firstPreUpload;
    }
}
