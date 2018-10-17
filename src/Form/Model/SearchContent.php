<?php

/**
 * Model for search
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Form\Model;

/**
 * Class SearchContent
 *
 * @package App\Form\Model
 */
class SearchContent
{
    /**
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get search data
     *
     * @return array
     */
    public function getSearchData()
    {
        $tab = [];
        if (!empty($this->name)) {
            $tab['name'] = $this->name;
        }

        return $tab;
    }
}
