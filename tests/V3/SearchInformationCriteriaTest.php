<?php

declare(strict_types=1);

namespace V3;

use Ekvio\Integration\Sdk\Common\Method;
use Ekvio\Integration\Sdk\V3\Information\SearchInformationCriteria;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SearchInformationCriteriaTest extends TestCase
{
    public function testBuildEmptyCriteria()
    {
        $c = SearchInformationCriteria::build();

        $this->assertEquals(Method::POST, $c->method());
        $this->assertEquals([], $c->queryParams());
        $this->assertEquals([], $c->body());
    }

    public function testExceptionSearchFieldIfEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        SearchInformationCriteria::build()->onlyFields([]);
    }

    public function testExceptionSearchFieldIfArrayContainsNotString()
    {
        $this->expectException(InvalidArgumentException::class);
        SearchInformationCriteria::build()->onlyFields(['', 1234, 'hello']);
    }
    public function testSuccessBuildSearchField()
    {
        $c = SearchInformationCriteria::build()->onlyFields(['id', 'name']);

        $this->assertEquals(['fields' => 'id,name'], $c->queryParams());
    }

    public function testExceptionSearchOnlyCategoryIfNotPositiveInteger()
    {
        $this->expectException(InvalidArgumentException::class);
        SearchInformationCriteria::build()->onlyCategory(0);
    }

    public function testSuccessSearchOnlyCategory()
    {
        $c = SearchInformationCriteria::build()->onlyCategory(1);

        $this->assertEquals(['filters' => ['category' => 1]], $c->body());
    }

    public function testExceptionIfEmptyStatus()
    {
        $this->expectException(InvalidArgumentException::class);
        SearchInformationCriteria::build()->onlyStatus('');
    }

    public function testSuccessSearchOnlyStatusIfNotAllowedValue()
    {
        $this->expectException(InvalidArgumentException::class);
        SearchInformationCriteria::build()->onlyStatus('blocked');
    }

    public function testSuccessSearchOnlyStatus()
    {
        $c = SearchInformationCriteria::build()->onlyStatus('active');

        $this->assertEquals(['filters' => ['status' => 'active']], $c->body());
    }

    public function testExceptionSearchMaterialsIfEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        SearchInformationCriteria::build()->onlyMaterials([]);
    }

    public function testExceptionSearchMaterialsIfArrayContainsNotInteger()
    {
        $this->expectException(InvalidArgumentException::class);
        SearchInformationCriteria::build()->onlyMaterials([0, 1234]);
    }

    public function testExceptionSearchMaterialsIfArrayCountExceed500()
    {
        $this->expectException(InvalidArgumentException::class);
        $materials = array_fill(0, 501, 1);
        SearchInformationCriteria::build()->onlyMaterials($materials);
    }

    public function testSuccessSearchMaterials()
    {
        $c = SearchInformationCriteria::build()->onlyMaterials([1, 2]);

        $this->assertEquals(['filters' => ['materials' => [1,2]]], $c->body());
    }
}