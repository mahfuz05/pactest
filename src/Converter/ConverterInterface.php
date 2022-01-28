<?php
declare(strict_types=1);

namespace App\Converter;

interface ConverterInterface {

    public function convertFile(\SplFileInfo $file, string $format): string;

    public function support(string $format) : bool ;
}