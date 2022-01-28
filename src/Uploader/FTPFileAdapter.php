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
        $destination = getenv('ftp_destination');
       $d= $this->uploader->uploadFile($file,
        getenv('ftp_hostname'),
        getenv('ftp_username'),
        getenv('ftp_password'),
        $destination);
       if($d) return "ftp://uploads.ipedis.com/{$destination}/{$file->getFilename()}";
    }

    public function support(string $method): bool  {
        return $method === 'ftp';
    }
}