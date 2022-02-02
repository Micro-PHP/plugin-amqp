<?php

namespace Micro\Plugin\Amqp\Exception;

use Micro\Plugin\Amqp\Business\Message\MessageInterface;

class InvalidSerializedMessageClassDataException extends LogicException
{
    /**
     * @param string|null $messageData
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $messageData = null, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($this->createMessage($messageData), $code, $previous);
    }

    /**
     * @param string|null $messageData
     * @return string
     */
    protected function createMessage(string $messageData = null)
    {
        if(!$messageData) {
            return sprintf(
                'Source data is null and can not be deserialized to "%s" object',
                MessageInterface::class
            );
        }

        return sprintf('Impossible serialize data "%s" to "%s" object', $messageData, MessageInterface::class);
    }
}
