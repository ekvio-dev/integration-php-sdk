<?php

namespace Ekvio\Integration\Sdk\V3\Message;

interface Message
{
    public function copyMessages(array $messages): array;
    public function updateMessages(array $messages): array;
    public function createIndividualAssignments(array $assignments): array;
    public function search(MessageSearchCriteria $criteria): array;
}
