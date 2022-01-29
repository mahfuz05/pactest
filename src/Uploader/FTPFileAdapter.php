<?php

declare(strict_types=1);

namespace App\Uploader;

use App\Uploader\UploaderAdopterInterface;
use FTPStub\FTPUploader;
use Symfony\Component\Dotenv\Dotenv;

class FTPFileAdapter implements UploaderAdopterInterface
{

    private $uploader;
    function __construct(FTPUploader $uploader)
    {
        $this->uploader = $uploader;
        // $dotenv = new Dotenv();
        //$dotenv->load(__DIR__.'/../../.env');
    }

    public function upload(\SplFileInfo $file): string
    {
        $destination = $_ENV['ftp_destination'];
        $d = $this->uploader->uploadFile(
            $file,
            $_ENV['ftp_hostname'],
            $_ENV['ftp_username'],
            $_ENV['ftp_password'],
            $destination
        );
        if ($d) {
            return "ftp://uploads.ipedis.com/{$destination}/{$file->getFilename()}";
        };
        return '';
    }

    public function support(string $method): bool
    {
        return $method === 'ftp';
    }
}
