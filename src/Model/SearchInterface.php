<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 2019-01-19
 * Time: 16:10
 */

namespace App\Model;

interface SearchInterface
{
    public function getFilters(): array;
    public function getPage(): int;
}
