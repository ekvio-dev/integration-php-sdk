<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Event;

/**
 * Class EventStatisticCriteria
 * @package Ekvio\Integration\Sdk\V2\Event
 */
class EventStatisticCriteria
{
    private ?string $eventStatus;
    private array $eventType = [];
    private ?string $userStatus;
    private bool $isPost = false;

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
     * @return string
     */
    public function method(): string
    {
        return $this->isPost ? 'POST' : 'GET';
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