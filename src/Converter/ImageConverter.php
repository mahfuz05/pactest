<?php


class ImageConverter
{
/**
     * @var Con[]
     */
    private array $converters;

    /**
     * @param ConverterInterface[] $uploader
     */
    public function __construct(array $converters)
    {
        $this->converters = $converters;
    }

    public function convertFile(SplFileInfo $file, string $format ): string
    {
        foreach ($this->converters as $converter) {
            if ($converter->support($format)) {
                return $converter->convertFile($file, $format);
            }
        }

        throw new InvalidArgumentException('Invalid Type Selected');
    }
}