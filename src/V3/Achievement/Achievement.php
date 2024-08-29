<?php

namespace Ekvio\Integration\Sdk\V3\Achievement;

interface Achievement
{
    public function badgesStatistic(BadgesStatisticCriteria $criteria): array;
    public function badgesSearch(BadgesSearchCriteria $criteria): array;
}
