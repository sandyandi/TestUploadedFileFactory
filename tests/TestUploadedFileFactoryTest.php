<?php

use Sandyandi\TestUploadedFileFactory\TestUploadedFileFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TestUploadedFileFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Sandyandi\TestUploadedFileFactory\TestUploadedFileFactory
     */
    private $testUploadedFileFactory;

    public function setUp()
    {
        $this->testUploadedFileFactory = new TestUploadedFileFactory(__DIR__ . '/dupe-files');
    }

    public function testCreate()
    {
        $uploadedFile = $this->testUploadedFileFactory->create(__DIR__ . '/test-file.txt');

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\File\UploadedFile', $uploadedFile);
        $this->assertFileExists($uploadedFile->getPathname());

        $this->testUploadedFileFactory->tearDown();
    }

    public function testUnmovedTearDown()
    {
        $uploadedFile = $this->testUploadedFileFactory->create(__DIR__ . '/test-file.txt');

        $dupeFile = $uploadedFile->getDupeFile();

        $this->testUploadedFileFactory->tearDown();

        $this->assertFalse(is_file($dupeFile->getPath()));
        $this->assertFalse(is_dir(str_replace($dupeFile->getOriginalName(), '', $dupeFile->getPath())));
    }

    public function testMovedTearDown()
    {
        $uploadedFile = $this->testUploadedFileFactory->create(__DIR__ . '/test-file.txt');

        $dupeFile = $uploadedFile->getDupeFile();

        $uploadedFile->move(__DIR__ . '/move-dir', 'test-file.txt');

        $this->testUploadedFileFactory->tearDown();

        $this->assertFalse(is_file($dupeFile->getPath()));
        $this->assertFalse(is_file($dupeFile->getMovePath()));
        $this->assertFalse(is_dir(str_replace($dupeFile->getOriginalName(), '', $dupeFile->getPath())));
    }
}