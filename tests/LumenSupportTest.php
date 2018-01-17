<?php

namespace Websight\GcsProvider\Tests;

use PHPUnit\Framework\TestCase;
use Laravel\Lumen\Application;
use Illuminate\Config\Repository;
use Websight\GcsProvider\CloudStorageServiceProvider;

class LumenSupportTest extends TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var CloudStorageServiceProvider
     */
    protected $provider;

    public function setUp()
    {
        if (!class_exists(Application::class)) {
            $this->markTestSkipped();
        }

        $this->app = $this->setupApplication();
        $this->provider = $this->setupServiceProvider($this->app);

        parent::setUp();
    }

    protected function setupApplication()
    {
        $app = new Application(sys_get_temp_dir());
        $app->instance('config', new Repository());

        $app->withFacades();

        return $app;
    }

    protected function setupServiceProvider(Application $app)
    {
        $provider = new CloudStorageServiceProvider($app);
        $app->register($provider);
        $provider->boot();

        return $provider;
    }

    public function testFacadeIsExists()
    {
        $this->assertTrue(class_exists('Storage'));
    }

    public function testRequiredProvidersAreRegistered()
    {
        $this->assertTrue($this->app->has('filesystem'));
    }
}
