<?php


namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class ApplicationAware
 * @package App\Annotation
 * @Annotation
 * @Target("CLASS")
 */
class ApplicationAware
{
    public $applicationFieldName;
}