<?php

namespace Ekvio\Integration\Sdk\V3\Track;

interface Track
{
    public function statistic(TrackStatisticCriteria $criteria): array;
    public function statisticCold(TrackColdStatisticCriteria $criteria): array;
    public function search(TrackSearchCriteria $criteria): array;
    public function searchContent(TrackSearchContentCriteria $criteria): array;
}