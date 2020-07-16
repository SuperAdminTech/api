<?php


namespace App\Tests\Utils;

use Faker\Factory;
use Faker\Generator;

trait FakerTrait {

    /**
     * @param string $locale
     * @return Generator
     */
    protected function getFaker($locale = Factory::DEFAULT_LOCALE): Generator {
        return Factory::create($locale);
    }
}