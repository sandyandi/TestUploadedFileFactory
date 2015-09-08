<?php

namespace Sandyandi\TestUploadedFileFactory;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFilePublisher extends UploadedFile
{
    /**
     * @var Sandyandi\TestUploadFileFactory\DupeFile
     */
    private $dupeFile;

    public function __construct(DupeFile $dupeFile)
    {
        $this->dupeFile = $dupeFile;

        parent::__construct(
            $dupeFile->getPath(),
            $dupeFile->getOriginalName(),
            $dupeFile->getMimeType(),
            $dupeFile->getSize(),
            null,
            true
        );
    }

    public function move($directory, $name)
    {
        if ($move = parent::move($directory, $name))
            $this->dupeFile->move($directory, $name);

        return $move;
    }

    public function getDupeFile()
    {
        return $this->dupeFile;
    }
}