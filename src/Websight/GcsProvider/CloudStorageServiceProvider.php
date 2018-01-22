<?php

namespace Websight\GcsProvider;

use CedricZiel\FlysystemGcs\GoogleCloudStorageAdapter;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Storage;

/**
 * Class CloudStorageServiceProvider
 * Configures Google Cloud Storage Access for flysystem
 *
 * @package Websight\GcsProvider
 */
class CloudStorageServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        Storage::extend('gcs', function ($app, $config) {

            $adapterConfiguration = ['bucket' => $config['bucket']];
            $serviceBuilderConfig = [];

            $optionalServiceBuilder = null;

            if (array_key_exists('project_id', $config) && false === empty($config['project_id'])) {
                $adapterConfiguration += ['projectId' => $config['project_id']];
                $serviceBuilderConfig += ['projectId' => $config['project_id']];
            }

            if (array_key_exists('credentials', $config) && false === empty($config['credentials'])) {
                $serviceBuilderConfig += ['keyFilePath' => $config['credentials']];
                $optionalServiceBuilder = new StorageClient($serviceBuilderConfig);
            }

            $adapter = new GoogleCloudStorageAdapter($optionalServiceBuilder, $adapterConfiguration);

            return new Filesystem($adapter);
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->has('filesystem')) {
            $this->app->singleton('filesystem', function ($app) {
                /** @var \Laravel\Lumen\Application $app */
                return $app->loadComponent(
                    'filesystems',
                    \Illuminate\Filesystem\FilesystemServiceProvider::class,
                    'filesystem'
                );
            });

            $this->app->singleton(
                \Illuminate\Contracts\Filesystem\Factory::class,
                function ($app) {
                    return new \Illuminate\Filesystem\FilesystemManager($app);
                }
            );
        }

        $this->registerFacades();
    }

    /**
     * Register Storage facade.
     *
     * @return void
     */
    protected function registerFacades()
    {
        if (!class_exists('Storage')) {
            class_alias(\Illuminate\Support\Facades\Storage::class, 'Storage');
        }
    }

    /**
     * Decides wheter the current app is lumen.
     *
     * @return bool
     */
    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }
}
