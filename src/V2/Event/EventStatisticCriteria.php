<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Event;

use DateTimeImmutable;
use RuntimeException;

/**
 * Class EventStatisticCriteria
 * @package Ekvio\Integration\Sdk\V2\Event
 */
class EventStatisticCriteria
{
    private const MAX_FILTER_COUNT = 500;
    private ?string $eventStatus = null;
    private array $eventType = [];
    private ?string $userStatus = null;
    private ?DateTimeImmutable $toDate = null;
    private ?DateTimeImmutable $afterDate = null;
    private array $login = [];
    private array $event = [];

    /**
     * ProgramSearchCriteria constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param array $criteria
     * @return static
     */
    public static function build(array $criteria = []): self
    {
        $self = new self();
        if (!$criteria) {
            return $self;
        }

        return $self;
    }

    /**
     * @return $this
     */
    public function onlyActiveEvent(): self
    {
        $self = clone $this;
        $self->eventStatus = 'active';
        return $self;
    }

    /**
     * @return $this
     */
    public function onlyHideEvent(): self
    {
        $self = clone $this;
        $self->eventStatus = 'hide';
        return $self;
    }

    /**
     * @param array $type
     * @return $this
     */
    public function withType(array $type): self
    {
        $self = clone $this;
        $self->eventType = array_filter($type);
        return $self;
    }

    /**
     * @return $this
     */
    public function onlyActiveUser(): self
    {
        $self = clone $this;
        $self->userStatus = 'active';
        return $self;
    }

    /**
     * @return $this
     */
    public function onlyBlockedUser(): self
    {
        $self = clone $this;
        $self->userStatus = 'blocked';
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
        return (count($this->login) > 0 || count($this->event) ) ? 'POST' : 'GET';
    }

    /**
     * @param array $logins
     * @return $this
     */
    public function filterByLogin(array $logins): self
    {
        if(count($logins) > self::MAX_FILTER_COUNT) {
            throw new RuntimeException(sprintf('Maximum count login is %s', self::MAX_FILTER_COUNT));
        }

        $self = clone $this;
        $self->login = array_filter($logins, function ($login) {
            return is_string($login) && mb_strlen($login) > 0;
        });
        return $self;
    }

    public function filterById(array $events): self
    {
        if(count($events) > self::MAX_FILTER_COUNT) {
            throw new RuntimeException(sprintf('Maximum count id is %s', self::MAX_FILTER_COUNT));
        }

        $self = clone $this;
        $self->event = array_filter($events, function ($eventId) {
            return is_int($eventId) && $eventId > 0;
        });
        return $self;
    }

    /**
     * @return array
     */
    public function queryParams(): array
    {
        $params = [];
        if($this->eventStatus) {
            $params['event_status'] = $this->eventStatus;
        }

        if($this->eventType) {
            $params['event_type'] = implode(',', $this->eventType);
        }

        if($this->userStatus) {
            $params['user_status'] = $this->userStatus;
        }

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
        $body = ['filters' => []];
        if($this->login) {
            $body['filters']['login'] = $this->login;
        }

        if($this->event) {
            $body['filters']['event'] = $this->event;
        }

        return $body;
    }
}