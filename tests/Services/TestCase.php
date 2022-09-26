<?php

namespace YouCan\Tests\Services;

use Illuminate\Console\Application;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Application $app;

    protected function setUp(): void
    {
        if (!isset($this->app)) {
            $this->app = $this->createApplication();
        }
    }

    private function createApplication(): Application
    {
        return require __DIR__ . '/../../bootstrap.php';
    }
}