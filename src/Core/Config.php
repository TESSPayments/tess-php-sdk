<?php

namespace TessPayments\Core;

use Dotenv\Dotenv;

class Config
{
    private static $instance;
    private $values;

    private function __construct()
    {
        $this->loadEnv();
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->safeLoad();
        
        $this->values = [
            'merchant_key' => $_ENV['MERCHANT_KEY'] ?? '',
            'merchant_pass' => $_ENV['MERCHANT_PASSWORD'] ?? '',
            'timeout' => $_ENV['CONFIG_TIMEOUT'] ?? 30
        ];
    }

    public static function getBaseUrl(string $integrationType): string
    {
        $key = strtoupper("{$integrationType}_URL");
        $url = $_ENV[$key] ?? '';
        
        if (empty($url)) {
            throw new \RuntimeException("Base URL not configured for $integrationType");
        }
        
        return $url;
    }

    public function get(string $key)
    {
        return $this->values[$key] ?? null;
    }
}