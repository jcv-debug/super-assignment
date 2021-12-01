<?php

namespace Tests\src\SocialPost\Driver\Factory;

use Tests\src\SocialPost\Driver\DummyDriver;

/**
 * Class FictionalDriverFactory
 *
 * @package Tests\src\SocialPost\Driver\Factory
 */
class FictionalDriverFactory
{

    /**
     * Returns an instance of DummyDriver
     *
     * @return DummyDriver
     */
    public static function create()
    {
        return new DummyDriver();
    }
}
