<?php

namespace Hackathon\Infrastructure;

class EventHub
{
    
    private string $uri;
    
    public function __construct(
        private string $servicebusNamespace,
        private string $path,
        private string $sasKeyName,
        private string $sasKeyValue
    ) {
        $this->uri = $servicebusNamespace . '.servicebus.windows.net';
    }
    
    public function generateSasToken(): string
    {
        $targetUri = strtolower(rawurlencode(strtolower($this->uri)));
        $expires = time();
        $expiresInMins = 60;
        $week = 60*60*24*7;
        $expires = $expires + $week;
        $toSign = $targetUri . "\n" . $expires;
        $signature = rawurlencode(base64_encode(hash_hmac('sha256', $toSign, $this->sasKeyValue, TRUE)));
        $token = "SharedAccessSignature sr=" . $targetUri . "&sig=" . $signature . "&se=" . $expires . "&skn=" . $this->sasKeyName;
        return $token;
    }
    
    public function getUri(): string
    { return $this->uri; }
    
    public function getPath(): string
    { return $this->path; }
    
}
