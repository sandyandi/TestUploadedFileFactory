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
    private $dupePaths = [];

    public function __construct($dupeFilesDir = '')
    {
        $this->dupeFilesDir = ($dupeFilesDir ? rtrim($dupeFilesDir, DIRECTORY_SEPARATOR) : __DIR__ . '/../../dupe-files');
    }

    public function create($path)
    {
        if (!is_file($path)) throw new FileNotFoundException($path);
        if (!is_readable($path)) throw new AccessDeniedException($path);

        $dupeFile = $this->createDupeFile($path);

        $this->dupePaths[] = $dupeFile->getPath();

        return new UploadedFilePublisher($dupeFile, $this);
    }

    protected function createDupeFile($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        do {
            $dupeFilename = uniqid() . '.' . $ext;
            $dupeFilePath = $this->uploadedFilesDir($dupeFilename);
        } while (is_file($dupeFilePath));

        if (copy($path, $dupeFilePath)) {
            return new DupeFile($dupeFilePath);
        }

        throw new AccessDeniedException($dupeFilePath);
    }

    protected function uploadedFilesDir($path)
    {
        return $this->dupeFilesDir . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function move(DupeFile $dupeFile, $directory, $name)
    {
        foreach ($this->dupePaths as $i => $dupePath) {
            if ($dupePath === $dupeFile->getPath()) {
                $movePath = rtrim($directpry, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;
                $this->dupePaths[$i] = $movePath;

                break;
            }
        }
    }

    public function tearDown()
    {
        foreach ($this->dupePaths as $dupePath) @unlink($dupePath);
    }
}