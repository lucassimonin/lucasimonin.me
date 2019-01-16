<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 20/10/2018
 * Time: 08:50
 */

namespace App\EventListener;

use App\Utils\FileUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Media;

class MediaUploadListener
{
    private $uploader;

    public function __construct(FileUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Media) {
            return;
        }
        if ($fileName = $entity->getFileName()) {
            $entity->setFile(new File($this->getFullPathMedia($fileName)));
        }
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof Media) {
            return;
        }

        $file = $entity->getFile();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            if (null !== $entity->getFileName()) {
                unlink($this->getFullPathMedia($entity->getFileName()));
            }
            $entity->setFileName($fileName);
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setFileName($file->getFilename());
        }
    }

    /**
     * @param string $fileName
     * @return string
     */
    private function getFullPathMedia(string $fileName): string
    {
        return $this->uploader->getTargetDirectory().'/'.$fileName;
    }
}
