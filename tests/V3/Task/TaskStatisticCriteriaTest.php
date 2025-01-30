<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\Tests\V3\Task;

use Ekvio\Integration\Sdk\V3\Task\TaskStatisticCriteria;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TaskStatisticCriteriaTest extends TestCase
{
    public function testBuildEmptyCriteria()
    {
        $c = TaskStatisticCriteria::build();

        $this->assertEquals('POST', $c->method());
        $this->assertEquals([], $c->queryParams());
        $this->assertEquals([], $c->body());
    }

    public function testExceptionWithInvalidTaskId()
    {
        $this->expectException(InvalidArgumentException::class);
        TaskStatisticCriteria::build()
            ->onlyTasks([1, -2, 3, '4']);
    }

    public function testExceptionIfExceedMaxCount()
    {
        $this->expectException(InvalidArgumentException::class);
        TaskStatisticCriteria::build()
            ->onlyTasks(array_fill(1, 501, 100));
    }

    public function testCriteriaWithTasks()
    {
        $c = TaskStatisticCriteria::build()
            ->onlyTasks([1, 2, 3]);

        $this->assertEquals(['tasks' => '1,2,3'], $c->queryParams());
        $this->assertEquals([], $c->body());
    }

    public function testExceptionIfMoreOneAnswerStatus()
    {
        $this->expectException(InvalidArgumentException::class);
        TaskStatisticCriteria::build()
            ->onlyAnswerStatus(['completed', 'failed']);
    }

    public function testExceptionIfAnswerStatusInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        TaskStatisticCriteria::build()
            ->onlyAnswerStatus(['invalid']);
    }

    public function testCriteriaWithAnswerStatus()
    {
        $c = TaskStatisticCriteria::build()
            ->onlyAnswerStatus(['completed']);

        $this->assertEquals(['answer_status' => 'completed'], $c->queryParams());
        $this->assertEquals([], $c->body());
    }

    public function testCriteriaWithToDate()
    {
        $dt = (new \DateTimeImmutable())
            ->setTimestamp(1663941000)
            ->setTimezone(new \DateTimeZone('Europe/Moscow'));

        $c = TaskStatisticCriteria::build()
            ->onlyToDate($dt);

        $this->assertEquals(['to_date' => '2022-09-23T16:50:00+03:00'], $c->queryParams());
        $this->assertEquals([], $c->body());
    }

    public function testCriteriaWithAfterDate()
    {
        $dt = (new \DateTimeImmutable())
            ->setTimestamp(1663941000)
            ->setTimezone(new \DateTimeZone('Europe/Moscow'));

        $c = TaskStatisticCriteria::build()
            ->onlyAfterDate($dt);

        $this->assertEquals(['after_date' => '2022-09-23T16:50:00+03:00'], $c->queryParams());
        $this->assertEquals([], $c->body());
    }

    public function testCriteriaWithUnknownRole()
    {
        $role = 'unknown';
        $this->expectException(InvalidArgumentException::class);
        TaskStatisticCriteria::build()->onlyRole($role);
    }

    public function testCriteriaWithRole()
    {
        $role = 'administrator';
        $c = TaskStatisticCriteria::build()->onlyRole($role);
        $this->assertEquals(['filters' => ['role' => [$role]]], $c->body());
    }

    public function testCriteriaWithReviewers()
    {
        $reviewers = [1, 2, 3];
        $c = TaskStatisticCriteria::build()->onlyReviewers($reviewers);
        $this->assertEquals(['filters' => ['reviewer' => $reviewers]], $c->body());
    }
}