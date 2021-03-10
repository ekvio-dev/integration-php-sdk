<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Training;

use DateTimeImmutable;

/**
 * Class TrainingStatisticCriteria
 * @package Ekvio\Integration\Sdk\V2\Training
 */
class TrainingStatisticCriteria
{
    private const METHOD = 'GET';
    private ?DateTimeImmutable $toDate = null;
    private ?DateTimeImmutable $afterDate = null;
    /**
     * TrainingSearchCriteria constructor.
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
     * @param DateTimeImmutable $dateTime
     * @return $this
     */
    public function withToDate(DateTimeImmutable $dateTime): self
    {
        $self = clone $this;
        $self->toDate = $dateTime;
        return $self;
    }

    /**
     * @param DateTimeImmutable $dateTime
     * @return $this
     */
    public function withAfterDate(DateTimeImmutable $dateTime): self
    {
        $self = clone $this;
        $self->afterDate = $dateTime;
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

        if($this->toDate) {
            $params['to_date'] = $this->toDate->format(DATE_ATOM);
        }

        if($this->afterDate) {
            $params['after_date'] = $this->afterDate->format(DATE_ATOM);
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