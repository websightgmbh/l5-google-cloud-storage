<?php

namespace Websight\GcsProvider;

use CedricZiel\FlysystemGcs\GoogleCloudStorageAdapter;
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
            $adapter = new GoogleCloudStorageAdapter(null, ['bucket' => $config['bucket']]);

            return new Filesystem($adapter);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Not needed
    }
}
