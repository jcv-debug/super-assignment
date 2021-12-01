<?php

namespace Tests\src\SocialPost\Service\Factory;

use Tests\src\SocialPost\Driver\Factory\FictionalDriverFactory;

use SocialPost\Hydrator\FictionalPostHydrator;
use SocialPost\Service\SocialPostService;

/**
 * Class SocialPostServiceFactory
 *
 * @package Tests\src\SocialPost\Service\Factory
 */
class SocialPostServiceFactory
{

    /**
     * Returns an initialized instance of SocialPostService
     *
     * @return SocialPostService
     */
    public static function create(): SocialPostService
    {
        $driver = FictionalDriverFactory::create();
        $hydrator = new FictionalPostHydrator();
        return new SocialPostService($driver, $hydrator);
    }
}
