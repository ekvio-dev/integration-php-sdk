<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Media;


interface Media
{
    public function create(array $media): array;
    public function search(MediaSearchCriteria $criteria): array;
}