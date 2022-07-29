<?php
namespace App\Interfaces;

interface INotificationClient {
    public function pushNotification(string $message, ?string $type = null);
    public function setWebhookUrl(string $webhookUrl): void;
}