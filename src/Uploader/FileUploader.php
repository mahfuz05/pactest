<?php

declare(strict_types=1);
namespace App\Uploader;

use InvalidArgumentException;
use SplFileInfo;

class FileUploader
{
    /**
     * @var UploaderAdopterInterface[]
     */
    private array $uploaders;

    /**
     * @param UploaderAdopterInterface[] $uploader
     */
    public function __construct(array $uploaders)
    {
        $this->uploaders = $uploaders;
    }

    public function uploadFile(string $uplodingMethod, SplFileInfo $file): string
    {
        foreach ($this->uploaders as $uploader) {
            if ($uploader->support($uplodingMethod)) {
                return $uploader->upload($file);
            }
        }

        throw new InvalidArgumentException('Invalid Type Selected');
    }
}