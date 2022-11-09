<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\LearningProgram;

/**
 * Class ProgramSearchCriteria
 * @package Ekvio\Integration\Sdk\V3\LearningProgram
 */
class ProgramSearchCategoryCriteria
{
    private const METHOD = 'GET';
    private array $fields = [];
    private array $include = [];
    private ?string $programStatus = null;
    private ?string $materialStatus = null;
    /**
     * ProgramSearchCategoryCriteria constructor.
     */
    private function __construct(){}

    /**
     * @param array $criteria
     * @return static
     */
    public static function build(array $criteria = []): self
    {
        $self = new self();
        if(!$criteria) {
            return $self;
        }

        return $self;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function withFields(array $fields): self
    {
        $self = clone $this;
        $self->fields = $fields;
        return $self;
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return self::METHOD;
    }

    /**
     * @return array
     */
    public function queryParams(): array
    {
        $params = [];

        if($this->fields) {
            $params['fields'] = implode(',', $this->fields);
        }

        return $params;
    }

    /**
     * @return array
     */
    public function body(): array
    {
        return [];
    }
}