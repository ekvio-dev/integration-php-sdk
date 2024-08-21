<?php

namespace Ekvio\Integration\Sdk\V3\Material;

use Ekvio\Integration\Sdk\V3\Task\MaterialStatisticCriteria;

interface Material
{
    public function createDocument(array $items): array;
    public function updateDocument(array $items): array;
    public function createLink(array $items): array;
    public function updateLink(array $items): array;
    public function createPdf(array $items): array;
    public function updatePdf(array $items): array;
    public function statistic(MaterialStatisticCriteria $criteria): array;
}