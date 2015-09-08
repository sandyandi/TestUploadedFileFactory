<?php

namespace Sandyandi\TestUploadedFileFactory;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class TestUploadedFileFactory
{
    /**
     * @var string
     */
    private $dupeFilesDir;

    /**
     * @var array
     */
    private $dupeFiles = [];

    public function __construct($dupeFilesDir = '')
    {
        $this->dupeFilesDir = ($dupeFilesDir ? rtrim($dupeFilesDir, DIRECTORY_SEPARATOR) : __DIR__ . '/../../dupe-files');
    }

    public function create($path)
    {
        if (!is_file($path)) throw new FileNotFoundException($path);
        if (!is_readable($path)) throw new AccessDeniedException($path);

        $dupeFile = $this->createDupeFile($path);

        $this->dupeFiles[] = $dupeFile;

        return new UploadedFilePublisher($dupeFile, $this);
    }

    protected function createDupeFile($path)
    {
        $filename = basename($path);

        do {
            $dupeDir = $this->uploadedFilesDir(uniqid());
            $dupePath = $dupeDir . DIRECTORY_SEPARATOR . $filename;
        } while (is_file($dupePath));

        if (@mkdir($dupeDir) and @copy($path, $dupePath)) {
            return new DupeFile($dupePath);
        }

        throw new AccessDeniedException($dupePath);
    }

    protected function uploadedFilesDir($path)
    {
        return $this->dupeFilesDir . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function tearDown()
    {
        foreach ($this->dupeFiles as $dupeFile) {
            if ($dupeFile->isMoved()) {
                @unlink($dupeFile->getMovePath());
            } else {
                @unlink($dupeFile->getPath());
                @rmdir(str_replace($dupeFile->getOriginalName(), '', $dupeFile->getPath()));
            }
        }
    }
}