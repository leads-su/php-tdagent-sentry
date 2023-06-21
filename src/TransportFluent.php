<?php

namespace TdAgentSentry;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;
use Sentry\Options;
use Sentry\Response;
use Sentry\ResponseStatus;
use \Sentry\Serializer\PayloadSerializerInterface;
use \Sentry\Transport\TransportInterface;
use \Fluent\Logger\FluentLogger;

class TransportFluent implements TransportInterface
{
    public function __construct(
        Options $options,
        PayloadSerializerInterface $payloadSerializer,
        FluentLogger $fluentd
    ) {
        $this->options = $options;
        $this->fluentd = $fluentd;
        $this->payloadSerializer = $payloadSerializer;
    }

    public function send(\Sentry\Event $event): \GuzzleHttp\Promise\PromiseInterface
    {
        $dsn = $this->options->getDsn();

        $json = $this->payloadSerializer->serialize($event);
        $payload = json_decode($json, true);

        $tag = sprintf(
            'sentry.store.%d.%s',
            $dsn->getProjectId(),
            $dsn->getPublicKey()
        );

        if ($this->fluentd->post($tag, $payload)) {
            return new FulfilledPromise(
                new Response(ResponseStatus::createFromHttpStatusCode(200), $event)
            );
        } else {
            return new RejectedPromise(new Response(ResponseStatus::failed(), $event));
        }
    }

    public function close(?int $timeout = null): \GuzzleHttp\Promise\PromiseInterface
    {
        return new FulfilledPromise(true);
    }

    //######################################################################
    // PRIVATE
    //######################################################################

    /** @var Options  */
    private $options;

    /** @var FluentLogger */
    private $fluentd;

    /** @var PayloadSerializerInterface */
    private $payloadSerializer;
}
