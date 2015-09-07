<?php

namespace Sandyandi\TestUploadedFileFactory;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFilePublisher extends UploadedFile
{
    /**
     * @var Sandyandi\TestUploadedFileFactory\UploadedFileFactory
     */
    private $testUploadedFileFactory;

    /**
     * @var Sandyandi\TestUploadFileFactory\DupeFile
     */
    private $dupeFile;

    public function __construct(
        DupeFile $dupeFile,
        TestUploadedFileFactory $testUploadedFileFactory
    ) {
        $this->dupeFile = $dupeFile;
        $this->testUploadedFileFactory = $testUploadedFileFactory;

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
            $this->testUploadedFileFactory->move($this->dupeFile, $directory, $name);

        return $move;
    }
}