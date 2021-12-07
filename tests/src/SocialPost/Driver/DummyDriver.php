<?php

namespace Tests\src\SocialPost\Driver;

use Traversable;

use SocialPost\Driver\SocialDriverInterface;

/**
 * Class DummyDriver
 *
 * @package Tests\src\SocialPost\Driver
 */
class DummyDriver implements SocialDriverInterface
{

    /**
     * Gets a json datasource file contents and returns a Traversable object its data
     *
     * @param Int $page page number
     *
     * @return Traversable
     */
    public function fetchPostsByPage(int $page): Traversable
    {
        $dataFile = dirname(__FILE__) . '/../../../data/social-posts-response.json';
        $rawData = $this->_getDataSource($dataFile);
        return new \ArrayObject($rawData['data']['posts']);
    }

    /**
     * Reads json datasource file and returns its decoded data as an associative array
     *
     * @param String $filePath file system of the json file
     *
     * @return Array
     */
    private function _getDataSource(String $filePath): Array
    {
        return json_decode(file_get_contents($filePath), true);
    }
}
