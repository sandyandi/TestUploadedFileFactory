# TestUploadedFileFactory
## A simple Symfony\Component\HttpFoundation\File\UploadedFile factory for testing

### Installation
Run the following:
```
composer require sandyandi/test-uploadedfile-factory:1.0.*
```

### Usage

#### Instantiation
```php
use Sandyandi\TestUploadedFileFactory\TestUploadedFileFactory;

$testUploadedFileFactory = new TestUploadedFileFactory;
```
By default, it will use the `/tmp` directory located in `vendor/sandyandi/test-uploadedfile-factory` as its working directory. You can specify a different directory by specifying the directory path during instantiation:
```php
$workDir = 'path/to/your/work/dir';
$testUploadedFileFactory = new TestUploadedFileFactory($workDir);
```
** Note**: make sure the working directory is writeable.

#### Object creation
Once the factory has been instantiated, you can create an `UploadedFile` instance by doing the following:
```php
$pathToFile = 'path/to/your/file';
$uploadedFile = $testUploadedFileFactory->create($pathTofile);
```
The factory will create a copy of your file and put it in the working directory.

#### tearDown()
Be sure to call `$testUploadedFileFactory->tearDown();` in your test's `tearDown()` method to delete all created/moved files.
