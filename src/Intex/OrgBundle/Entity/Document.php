<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 31.10.2017
 * Time: 12:20
 */
namespace Intex\OrgBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Document
{
    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @param UploadedFile $file - Uploaded File
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    //add functions described here: http://symfony.com/doc/current/cookbook/doctrine/file_uploads.html
}