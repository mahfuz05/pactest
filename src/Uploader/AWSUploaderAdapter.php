<?php
declare(strict_types=1);

namespace App\Uploader;


use App\Uploader\UploaderAdopterInterface;
use S3Stub\Client;

class AWSUploaderAdapter implements UploaderAdopterInterface {
 
    /**
     * $uploader
     *
     * @var Client
     */
    private $uploader;
    function __construct(Client $uploader)
    {
        $this->uploader = $uploader;
    }

    public function upload(\SplFileInfo $file): string {
        $file = $this->uploader->send($file , getenv('s3_bucketname'));
        return $file->getPublicUrl();
    }

    public function support(string $method) : bool {
        return $method === 's3';
    }
}