<?php

namespace App\Services\Media;

use App\Repository\MediaRepository;
use App\Services\Core\BaseService;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MediaService
 * `
 * Object manager of media
 *
 * @package App\Services\Media
 */
class MediaService extends BaseService
{
    /**
     * @var MediaRepository
     */
    protected $mediaRepository;

    /**
     * MediaService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->addRepository('mediaRepository', Media::class);
    }

    /**
     * @param Media $media
     *
     * @return Media
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Media $media)
    {
        $media->setUpdated(new \DateTime());
        $this->getEntityManager()->persist($media);
        $this->getEntityManager()->flush();

        return $media;
    }


    /**
     * @param Media $media
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Media $media)
    {
        $this->getEntityManager()->remove($media);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Get all media
     *
     * @param array $filters
     *
     * @return mixed
     */
    public function queryForSearch($filters = array())
    {
        return $this->mediaRepository->queryForSearch($filters);
    }

    /**
     * Find all
     *
     * @return mixed
     */
    public function findAll()
    {
        return $this->mediaRepository->findAll();
    }

    /**
     * Find one by
     *
     * @param array $filters
     * @return mixed
     */
    public function findOneBy($filters = array())
    {
        return $this->mediaRepository->findOneBy($filters);
    }

}
