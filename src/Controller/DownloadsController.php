<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

class DownloadsController extends Controller {


    public function download($filename) {
       $file = WWW_ROOT . 'img\applications\9' . DS . $filename;
           if (!file_exists($file) || !is_readable($file)) {
            throw new NotFoundException('The file does not exist or is not readable.');
        }
        $fileHandle = fopen($file, 'rb');

        if (!$fileHandle) {
            throw new InternalErrorException('Failed to open the file.');
        }

        // Set content type and disposition headers
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Output file contents
        readfile($file);

        // Stop further rendering
        $this->autoRender = false;
    }
}
