<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Event;

/**
 * Interface Event
 * @package Ekvio\Integration\Sdk\V2\Event
 */
interface Event
{
    /**
     * @param EventSearchCriteria $criteria
     * @return array
     */
    public function search(EventSearchCriteria $criteria): array;

    /**
     * @param EventStatisticCriteria $criteria
     * @return array
     */
    public function statistic(EventStatisticCriteria $criteria): array;
}