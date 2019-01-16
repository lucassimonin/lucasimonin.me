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
     * @var string|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $firstName;

    /**
     * @var string|null
     */
    protected $lastName;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Get search data
     *
     * @return array
     */
    public function getSearchData(): array
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
