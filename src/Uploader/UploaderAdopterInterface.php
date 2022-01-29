<?php

declare(strict_types=1);

namespace App\Uploader;

use SplFileInfo;

interface UploaderAdopterInterface
{

    public function upload(SplFileInfo $file): string;

    public function support(string $method): bool;
}
