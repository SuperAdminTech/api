<?php


namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class ReaderAware
 * @package App\Annotation
 * @Annotation
 * @Target("CLASS")
 */
class ReaderAware
{
    public $readerFieldName;
}