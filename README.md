# Google Cloud Storage ServiceProvider for Laravel 5 Apps

[![Latest Stable Version](https://poser.pugx.org/websight/l5-google-cloud-storage/v/stable)](https://packagist.org/packages/websight/l5-google-cloud-storage) [![Total Downloads](https://poser.pugx.org/websight/l5-google-cloud-storage/downloads)](https://packagist.org/packages/websight/l5-google-cloud-storage) [![Latest Unstable Version](https://poser.pugx.org/websight/l5-google-cloud-storage/v/unstable)](https://packagist.org/packages/websight/l5-google-cloud-storage) [![License](https://poser.pugx.org/websight/l5-google-cloud-storage/license)](https://packagist.org/packages/websight/l5-google-cloud-storage)

Wraps [superbalist/flysystem-google-storage](https://github.com/Superbalist/flysystem-google-storage) in a Laravel 5 / 5.1 compatible
Service Provider.

## Configuration

1. Obtain the p12 certificate of a dedicated CloudPlatform Service Account
2. Add the service provider to your application in ``config/app.php``
   ```php
   Websight\GcsProvider\CloudStorageServiceProvider::class,
   ```

3. Add a disk to config/filesystems.php
   ```php
   'gcs' => [
       // Select the Google Cloud Storage Disk
       'driver'                               => 'gcs',
       // The id of your new service account
       'service_account'                      => 'service@account.iam.gserviceaccount.com',
       // The location of the p12 service account certificate
       'service_account_certificate'          => storage_path() . '/credentials.p12', 
       // The password you will be given when creating the service account
       'service_account_certificate_password' => 'yourpassword',
       // The bucket you want this disk to point at
       'bucket'                               => 'cloud-storage-bucket',
   ],
   ```

## Usage

Use it like any other Flysystem Adapter with the ``Storage``-Facade.

```php
// Put a private file on the 'gcs' disk which is a Google Cloud Storage bucket
Storage::disk('gcs')->put('test.png', file_get_contents(storage_path('/app/test.png')));

// Put a public-accessible file on the 'gcs' disk which is a Google Cloud Storage bucket
Storage::disk('gcs')->put(
    'test-public.png',
    file_get_contents(storage_path('/app/test-public.png')),
    \Illuminate\Contracts\Filesystem\Filesystem::VISIBILITY_PUBLIC
);

// Retrieve a file
$file = Storage::disk('gcs')->get('test.png');
```

## License (MIT)

Copyright (c) 2016 websight GmbH

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
