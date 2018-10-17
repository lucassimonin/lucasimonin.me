<?php

/**
 * Model for search
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Form\Model;

/**
 * Class SearchUser
 *
 * @package App\Form\Model\User
 */
class SearchUser
{
    /**
     * Id
     *
     * @var string
     */
    protected $id;

    /**
     * Email
     *
     * @var string
     */
    protected $email;

    /**
     * Firstname
     *
     * @var string
     */
    protected $firstName;

    /**
     * Lastname
     *
     * @var string
     */
    protected $lastName;

    /**
     * Set id
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Email
     *
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get Email
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstname
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get firstname
     *
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastname
     *
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get lastname
     *
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get search data
     *
     * @return array
     */
    public function getSearchData()
    {
        $tab = array();

        if (!empty($this->id)) {
            $tab['id'] = $this->id;
        }
        if (!empty($this->firstName)) {
            $tab['firstName'] = $this->firstName;
        }
        if (!empty($this->lastName)) {
            $tab['lastName'] = $this->lastName;
        }
        if (!empty($this->email)) {
            $tab['email'] = $this->email;
        }

        return $tab;
    }
}
