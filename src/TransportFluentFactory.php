<?php

namespace TdAgentSentry;

use Fluent\Logger\FluentLogger;
use \Sentry\Serializer\PayloadSerializer;
use \Sentry\Transport\TransportFactoryInterface;

class TransportFluentFactory implements TransportFactoryInterface
{
    public function __construct(FluentLogger $fluent)
    {
        $this->fluent = $fluent;
    }

    public function create(\Sentry\Options $options): \Sentry\Transport\TransportInterface
    {
        return new TransportFluent(
            $options,
            new PayloadSerializer($options),
            $this->fluent
        );
    }

    //######################################################################
    // PRIVATE
    //######################################################################

    /** @var FluentLogger */
    private $fluent;
}
