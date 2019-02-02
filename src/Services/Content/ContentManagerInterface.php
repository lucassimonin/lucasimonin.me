<?php

/**
 * Service
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Services\Content;

use App\Entity\ContentInterface;

/**
 * interface ContentManagerInterface
 * `
 * @package App\Services\Content
 */
interface ContentManagerInterface
{
    public function save(ContentInterface $content): ContentInterface;
    public function remove(ContentInterface $content): void;
    public function getContents(string $class, string $cacheKey, array $filters = [], array $orderBy = []);
}
