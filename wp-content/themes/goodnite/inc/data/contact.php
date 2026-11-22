<?php
declare(strict_types=1);

/** Configure approved Indian phone numbers in the server environment. */
$whatsapp = preg_replace('/\D+/', '', (string) (getenv('GOODNITE_WHATSAPP_NUMBER') ?: ''));
$phone = preg_replace('/[^\d+]/', '', (string) (getenv('GOODNITE_PHONE_NUMBER') ?: ''));
return [
    'whatsapp' => preg_match('/^91\d{10}$/', $whatsapp) ? $whatsapp : null,
    'phone' => preg_match('/^\+91\d{10}$/', $phone) ? $phone : null,
];
