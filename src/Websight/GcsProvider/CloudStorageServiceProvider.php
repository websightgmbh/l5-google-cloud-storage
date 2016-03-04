<?php

namespace Websight\GcsProvider;

use ErrorException;
use Google_Auth_AssertionCredentials;
use Google_Client;
use Google_Service_Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Storage;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

/**
 * Class CloudStorageServiceProvider
 * Configures Google Cloud Storage Access for flysystem
 *
 * @package Websight\GcsProvider
 */
class CloudStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('gcs', function ($app, $config) {
            $credentials = new Google_Auth_AssertionCredentials(
                $config['service_account'],
                [
                    Google_Service_Storage::DEVSTORAGE_FULL_CONTROL
                ],
                file_get_contents($config['service_account_certificate']),
                $config['service_account_certificate_password']
            );

            $client = new Google_Client();
            $client->setAssertionCredentials($credentials);

            $service = new Google_Service_Storage($client);
            $adapter = new GoogleStorageAdapter($service, $config['bucket']);

            return new Filesystem($adapter);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Not needed
    }
}
