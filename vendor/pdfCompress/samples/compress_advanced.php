<?php
//include the autoloader
require_once('../vendor/autoload.php');
//if manual installation has been used comment line that requires the autoload and uncomment this line:
//require_once('../init.php');


use Ilovepdf\CompressTask;


// you can call task class directly
// to get your key pair, please visit https://developer.ilovepdf.com/user/projects
$myTask = new CompressTask('project_public_2394190bcec4b66d98ce87301026dc28_fDMr58d621a9a78c4f7b1fa0f56b880e314a2','secret_key_cf5f13b3aedc070409ce0a491c4631a7_4OGFC89a92d65b3ceda37454bdcdbcaa37810');

// file var keeps info about server file id, name...
// it can be used latter to cancel file
$file = $myTask->addFile('/path/to/file/document.pdf');

// we can set rotate to file
$file->setRotation(90);

// set compression level
$myTask->setCompressionLevel('extreme');

// and set name for output file.
// the task will set the correct file extension for you.
$myTask->setOutputFilename('lowlow_compression');

// process files
$myTask->execute();

// and finally download file. If no path is set, it will be downloaded on current folder
$myTask->download('path/to/download');