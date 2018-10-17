<?php

/**
 * Service
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Services\Core;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Abstract class for the service which manage the Entity Manager
 *
 * @author Jerphagnon Adrien <woydadrien@gmail.com>
 * @author Paulin Vincent
 * @author Simonin Lucas <lsimonin2@gmail.com>
 */
abstract class BaseService
{

    /**
     * @var EntityManagerInterface The Entity Manager
     */
    protected $em;

    /**
     * BaseService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * Getter of the Entity Manager
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Add a repository to this service
     *
     * @param integer $key   Key
     * @param string  $class Class
     *
     * @return void
     */
    public function addRepository($key, $class)
    {
        $this->$key = $this->em->getRepository($class);
    }

    /**
     * Add a service to this service
     *
     * @param integer $key     Key
     * @param string  $service Class
     *
     * @return void
     */
    public function addService($key, $service)
    {
        $this->$key = $service;
    }
}
