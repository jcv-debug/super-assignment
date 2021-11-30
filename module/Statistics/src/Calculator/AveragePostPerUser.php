<?php

declare(strict_types = 1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class AveragePostPerUser extends AbstractCalculator
{
    protected const UNITS = 'posts';
    /**
     * @var array
     */
    private $collection = [];
    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $authorID = $postTo->getAuthorId() ?? 'Unknown';
        // Initialize authorId collection
        if(!isset($this->collection[$authorID])) {
            $this->collection[$authorID] = (object)[
                'name' => $postTo->getAuthorName() === null ? "Unknown (#$authorID)" : $postTo->getAuthorName(),
                'count' => 0,
            ];
        }
        // Add count to collection
        $this->collection[$authorID]->count++;
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $stats = new StatisticsTo();
        foreach ($this->collection as $authorID => $authorStats) {
            $child = (new StatisticsTo())
                ->setName($authorID)
                ->setLabel($authorStats->name)
                ->setValue($authorStats->count)
                ->setUnits(self::UNITS);

            $stats->addChild($child);
        }
        return $stats;
    }
}
