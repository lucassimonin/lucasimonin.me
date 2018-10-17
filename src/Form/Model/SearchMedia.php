<?php

/**
 * Model for search
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Form\Model;

/**
 * Class SearchMedia
 *
 * @package App\Form\Model\Form\Model
 */
class SearchMedia
{

    /**
     * Search
     *
     * @var string
     */
    protected $search;

    /**
     * Accepted
     *
     * @var string
     */
    protected $accepted;

    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param string $search
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

    /**
     * @return string
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * @param string $accepted
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;
    }



    /**
     * Get search data
     *
     * @return array
     */
    public function getSearchData()
    {
        $tab = array();

        if (!empty($this->search)) {
            $tab['search'] = $this->search;
        }
        if (!empty($this->accepted)) {
            $tab['accepted'] = $this->accepted;
        }


        return $tab;
    }
}
