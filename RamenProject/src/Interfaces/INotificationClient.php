<?php
namespace App\Interfaces;

interface INotificationClient {
    public function pushNotification(string $message, ?string $type = null): bool;
    public function setWebhookUrl(string $webhookUrl): void;
}