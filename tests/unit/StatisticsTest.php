<?php

declare(strict_types = 1);

namespace Tests\unit;

use PHPUnit\Framework\TestCase;

use Statistics\Dto\ParamsTo;
use Statistics\Enum\StatsEnum;
use Statistics\Service\Factory\StatisticsServiceFactory;
use Statistics\Extractor\StatisticsToExtractor;

use Tests\src\SocialPost\Service\Factory\SocialPostServiceFactory;

/**
 * Class StatisticsTest
 *
 * @package Tests\unit
 */
class StatisticsTest extends TestCase
{

    /**
     * @var string $initialized sets initialized flag
     */
    private $_initialized = false;

    /**
     * This method is called before every test of this test class, it initializes Services and Data to serve tests.
     * 
     * @return void
     */
    public function setUp(): void
    {
        // Call parents
        parent::setUp();
        // Set & get raw data
        if (true === $this->_initialized) {
            return;
        }
        // Initialize services & extractor
        $this->statsService = StatisticsServiceFactory::create();
        $this->socialService = SocialPostServiceFactory::create();
        $this->extractor = new StatisticsToExtractor();
        // Fetch posts
        $this->posts = $this->socialService->fetchPosts();
        // Set initialized flag
        $this->_initialized = true;
    }

    /**
     * This method tests the statistics for total posts per week within a given timeframe
     * 
     * @return void
     */
    public function testTotalPostsPerWeek(): void
    {
        // Build Total Posts Per Week params
        $params = $this->_buildParams(
            StatsEnum::TOTAL_POSTS_PER_WEEK,
            new \Datetime('2018-08-01')
        );
        // Calculate stats
        $stats = $this->statsService->calculateStats($this->posts, $params);
        // Extract
        $subjects = $this->extractor->extract($stats, []);
        /** 
         ******** ASSERTIONS *******
         */
        // Asserts - #1 Validate data output type
        $this->assertIsArray($subjects);
        // Asserts - #2 Validate key existence
        $this->assertTrue(isset($subjects['children'][0]['children'][0]['value']));
        // Asserts - #2 Validate calculated value
        $this->assertEquals($subjects['children'][0]['children'][0]['value'], 40);
    }

    /**
     * This method tests the statistics for the average posts per user within a given timeframe
     *
     * @return void
     */
    public function testAveragePostsPerUser(): void
    {
        // Build Total Posts Per Week params
        $params = $this->_buildParams(
            StatsEnum::AVERAGE_POST_NUMBER_PER_USER,
            new \Datetime('2018-08-01')
        );
        // Calculate stats
        $stats = $this->statsService->calculateStats($this->posts, $params);
        // Extract
        $subjects = $this->extractor->extract($stats, []);
        /**
         ******** ASSERTIONS *******
         */
        // Asserts - #1 Validate data output type
        $this->assertIsArray($subjects);
        // Asserts - #2 Validate key existence
        $this->assertTrue(isset($subjects['children'][0]['children'][0]['value']));
        // Asserts - #2 Validate calculated value
        $this->assertEquals($subjects['children'][0]['children'][0]['value'], 10);
    }

    /**
     * This method tests the statistics for the average posts length within a given timeframe
     *
     * @return void
     */
    public function testAveragePostsLength(): void
    {
        // Build Total Posts Per Week params
        $params = $this->_buildParams(
            StatsEnum::AVERAGE_POST_LENGTH,
            new \Datetime('2018-08-01')
        );
        // Calculate stats
        $stats = $this->statsService->calculateStats($this->posts, $params);
        // Extract
        $subjects = $this->extractor->extract($stats, []);
        /**
         ******** ASSERTIONS *******
         */
        // Asserts - #1 Validate data output type
        $this->assertIsArray($subjects);
        // Asserts - #2 Validate key existence
        $this->assertTrue(isset($subjects['children'][0]['value']));
        // Asserts - #2 Validate calculated value
        $this->assertEquals($subjects['children'][0]['value'], 495.25);
    }

    /**
     * This method tests the statistics for the longest post length within a given timeframe
     *
     * @return void
     */
    public function testLongestPostLength(): void
    {
        // Build Total Posts Per Week params
        $params = $this->_buildParams(
            StatsEnum::MAX_POST_LENGTH,
            new \Datetime('2018-08-01')
        );
        // Calculate stats
        $stats = $this->statsService->calculateStats($this->posts, $params);
        // Extract
        $subjects = $this->extractor->extract($stats, []);
        /**
         ******** ASSERTIONS *******
         */
        // Asserts - #1 Validate data output type
        $this->assertIsArray($subjects);
        // Asserts - #2 Validate key existence
        $this->assertTrue(isset($subjects['children'][0]['value']));
        // Asserts - #2 Validate calculated value
        $this->assertEquals($subjects['children'][0]['value'], 638);
    }

    /**
     * Returns an array with stats params, which provides Statistics module data on what to calculate and within what timeframe
     * 
     * @param String   $enum with stats type
     * @param Datetime $date date from which time range is calculated from
     *
     * @return Array
     */
    private function _buildParams(String $enum, \Datetime $date): array
    {
        // Calculates first and last day of the month from given date
        $startDate = (clone $date)->modify('first day of this month');
        $endDate   = (clone $date)->modify('last day of this month');
        // Compile array
        return [
            (new ParamsTo())
                ->setStatName($enum)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
        ];
    }

}
