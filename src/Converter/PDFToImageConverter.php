<?php
declare(strict_types=1);

namespace App\Converter;

use PDFStub\Client;

class PDFToImageConverter implements ConverterInterface {
    
    private $imageConverter;
    
    public function __construct(Client $client)
    {
        $this->imageConverter = $client;
    }

    public function convertFile(\SplFileInfo $file, string $format): string {
       
        return $this->imageConverter->convertFile($file, $format);
    
    }

    public function support(string $format) : bool {
        return getenv('converter_website') === 'pdf-convertor.com' && in_array($format, [
            'webp',
            'jpg',
            'png'
        ]);
    }
}