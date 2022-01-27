<?php
declare(strict_types=1);

namespace App\Uploader;

use DropboxStub\DropboxClient;
use SplFileInfo;

class DropboxUploaderAdapter implements UploaderAdopterInterface
{
    private $dropboxClient;
     public function __construct(DropboxClient $dropboxClient)
     {
         $this->dropboxClient=$dropboxClient;
         
     }

     public function upload(\SplFileInfo $file): string
     {

        return $this->dropboxClient->upload($file);
        
         
     }

     public function support(string $method) : bool {
        return $method === 'dropbox';
    }
}