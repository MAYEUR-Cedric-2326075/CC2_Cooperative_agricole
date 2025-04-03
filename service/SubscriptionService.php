<?php

namespace service;

class SubscriptionService
{
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . '/../../data/Json/subscriptions.json')
    {
        $this->filePath = $filePath;
    }

    public function getAllSubscriptions(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        return json_decode($json, true) ?? [];
    }

    public function saveSubscriptions(array $subs): bool
    {
        return file_put_contents($this->filePath, json_encode($subs, JSON_PRETTY_PRINT)) !== false;
    }

    public function subscribe(string $customerEmail, string $managerEmail): bool
    {
        $subs = $this->getAllSubscriptions();

        if (!isset($subs[$managerEmail])) {
            $subs[$managerEmail] = [];
        }

        if (!in_array($customerEmail, $subs[$managerEmail])) {
            $subs[$managerEmail][] = $customerEmail;
        }

        return $this->saveSubscriptions($subs);
    }

    public function unsubscribe(string $customerEmail, string $managerEmail): bool
    {
        $subs = $this->getAllSubscriptions();

        if (isset($subs[$managerEmail])) {
            $subs[$managerEmail] = array_filter(
                $subs[$managerEmail],
                fn($email) => $email !== $customerEmail
            );
        }

        return $this->saveSubscriptions($subs);
    }

    public function getSubscribersOf(string $managerEmail): array
    {
        $subs = $this->getAllSubscriptions();
        return $subs[$managerEmail] ?? [];
    }

    public function getSubscriptionsOf(string $customerEmail): array
    {
        $subs = $this->getAllSubscriptions();
        $result = [];
        foreach ($subs as $manager => $customers) {
            if (in_array($customerEmail, $customers)) {
                $result[] = $manager;
            }
        }
        return $result;
    }
}
