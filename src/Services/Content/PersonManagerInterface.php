<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 2019-01-19
 * Time: 11:47
 */

namespace App\Services\Content;

use App\Entity\Person;

interface PersonManagerInterface
{
    public function find(): Person;
    public function save(Person $person): Person;
}
