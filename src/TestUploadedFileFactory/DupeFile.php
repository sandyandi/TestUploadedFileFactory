<?php

namespace Sandyandi\TestUploadedFileFactory;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

class DupeFile
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $originalName;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var integer
     */
    private $size;

    /**
     * @var boolean
     */
    private $isMoved = false;

    /**
     * @var string
     */
    private $movePath;

    public function __construct($path)
    {
        $mimeTypeGuesser = MimeTypeGuesser::getInstance();

        $this->path = $path;
        $this->originalName = basename($path);
        $this->mimeType = $mimeTypeGuesser->guess($path);
        $this->size = filesize($path);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getOriginalName()
    {
        return $this->originalName;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function move($directory, $name)
    {
        @rmdir(str_replace($this->getOriginalName(), '', $this->getPath()));

        $this->movePath = $directory . DIRECTORY_SEPARATOR . $name;
        $this->isMoved = true;
    }

    public function getMovePath()
    {
        return $this->movePath;
    }

    public function isMoved()
    {
        return $this->isMoved;
    }
}