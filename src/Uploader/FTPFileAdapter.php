<?php
declare(strict_types=1);
namespace App\Uploader;

use App\Uploader\UploaderAdopterInterface;
use FTPStub\FTPUploader;

class FTPFileAdapter implements UploaderAdopterInterface {
 
    private $uploader;
    function __construct(FTPUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function upload(\SplFileInfo $file): string {
       $d= $this->uploader->uploadFile($file,
        '',
       '',
       ' string $password',
       '/');
       if($d) return $file->getFilename();
    }

    public function support(string $method): bool  {
        return $method === 'ftp';
    }
}