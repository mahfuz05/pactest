<?php
declare(strict_types=1);

namespace App\Converter;

use PDFStub\Client;

class ImageConverter implements Converter {
    
    private $imageConverter;
    
    public function __construct(Client $client)
    {
        $this->imageConverter = $client;
    }

    public function convertFile(\SplFileInfo $file, string $format): string {
       
        return $this->imageConverter->convertFile($file, $format);
    
    }
}