<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 2019-01-19
 * Time: 11:47
 */

namespace App\Services\Content;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;

class PersonManager implements PersonManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * PersonManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function find(): Person
    {
        $person = $this->getRepository()->findAll();

        return !empty($person) ? $person[0] : new Person();
    }

    public function getRepository()
    {
        return $this->em->getRepository(Person::class);
    }

    public function save(Person $person): Person
    {
        $this->em->persist($person);
        $this->em->flush();

        return $person;
    }
}
