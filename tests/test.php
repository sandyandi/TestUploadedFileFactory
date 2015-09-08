<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Sandyandi\TestUploadedFileFactory\TestUploadedFileFactory;

$testUploadedFileFactory = new TestUploadedFileFactory(__DIR__ . '/dupe-files');

$uploadedFile = $testUploadedFileFactory->create(__DIR__ . '/test-file.txt');

var_dump($uploadedFile->getDupeFile());