<?php
declare(strict_types=1);

namespace App\Converter;

interface Converter {

    public function convertFile(\SplFileInfo $file, string $format): string;
}