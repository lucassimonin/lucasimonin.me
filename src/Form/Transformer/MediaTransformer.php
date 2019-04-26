<?php

namespace App\Form\Transformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use App\Entity\Media;

class MediaTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($media)
    {
        if (null === $media) {
            return null;
        }

        return $media;
    }

    public function reverseTransform($mediaNumber)
    {
        if (!$mediaNumber) {
            return null;
        }

        return $this->entityManager
            ->getRepository(Media::class)
            ->findOneBy(['id' => $mediaNumber]);
    }
}
