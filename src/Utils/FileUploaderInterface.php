<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 20/10/2018
 * Time: 08:48
 */

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface
{
    public function upload(UploadedFile $file): string;
    public function getTargetDirectory(): string;
}
