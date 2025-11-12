<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Material;

use DateTimeInterface;
use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class MaterialSearchCriteria extends Criteria
{
    private const MATERIAL_STATUS = ['active', 'hide'];
    private const MATERIAL_TYPE = ['pdf', 'scorm', 'html', 'link', 'test', 'video', 'longread', 'document'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyMaterialStatus(string $status): self
    {
        Assert::notEmpty($status, 'Material status required.');
        Assert::inArray($status, self::MATERIAL_STATUS, 'Material status invalid. Use active or hide.');

        return $this->cloneWithParam('status', $status);
    }

    public function onlyMaterialType(array $types): self
    {
        Assert::notEmpty($types, 'Material type required.');
        Assert::allStringNotEmpty($types, 'Type should be not empty string');
        foreach ($types as $type) {
            Assert::inArray($type, self::MATERIAL_TYPE, 'Material type invalid. Use pdf, scorm, html, link, test, video, longread, document');
        }

        return $this->cloneWithParam('types', $types);
    }

    public function onlyFields(array $fields): self
    {
        Assert::notEmpty($fields, 'Field should not be empty');
        Assert::allStringNotEmpty($fields, 'Field should be not empty string');

        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyToDate(DateTimeInterface $dt): self
    {
        return $this->cloneWithParam('to_date', $dt);
    }

    public function onlyAfterDate(DateTimeInterface $dt): self
    {
        return $this->cloneWithParam('after_date', $dt);
    }

    public function method(): string
    {
        return Method::GET;
    }

}