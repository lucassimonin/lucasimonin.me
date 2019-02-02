<?php

/**
 * Model for search
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Model;

/**
 * Class SearchUser
 *
 * @package App\Model\User
 */
class SearchUser implements SearchInterface
{
    public static $limit = 20;

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

    /** @var int  */
    private $page = 1;

    public function __construct(array $queries)
    {
        $this->page = $queries['page'] ?? 1;
        $this->id = $queries['id'] ?? null;
        $this->firstName = $queries['firstName'] ?? null;
        $this->lastName = $queries['lastName'] ?? null;
        $this->email = $queries['email'] ?? null;
    }

    public function getFilters(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email
        ];
    }

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
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
