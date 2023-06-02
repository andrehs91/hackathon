<?php

namespace Hackathon\Controller;

use Hackathon\Infrastructure\EventHub;
use Symfony\Component\HttpClient\HttpClient;

class EventHubController
{
    
    public function __construct(
        private EventHub $eventHub
    ) {}
    
    public function send(array $message): void
    {
        $client = HttpClient::create([
            'headers' => [
                'Authorization' => $this->eventHub->generateSasToken(),
                'Content-Type' => 'application/atom+xml;type=entry;charset=utf-8',
                'Host' => $this->eventHub->getUri()
            ],
        ]);
        $url = 'https://' . $this->eventHub->getUri() . '/' . $this->eventHub->getPath() . '/messages';
        $response = $client->request('POST', $url, ['body' => json_encode($message)]);
    }
    
}
