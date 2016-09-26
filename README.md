# Google Cloud Storage ServiceProvider for Laravel 5 Apps

[![Latest Stable Version](https://poser.pugx.org/websight/l5-google-cloud-storage/v/stable)](https://packagist.org/packages/websight/l5-google-cloud-storage) [![Total Downloads](https://poser.pugx.org/websight/l5-google-cloud-storage/downloads)](https://packagist.org/packages/websight/l5-google-cloud-storage) [![Latest Unstable Version](https://poser.pugx.org/websight/l5-google-cloud-storage/v/unstable)](https://packagist.org/packages/websight/l5-google-cloud-storage) [![License](https://poser.pugx.org/websight/l5-google-cloud-storage/license)](https://packagist.org/packages/websight/l5-google-cloud-storage)

Wraps [cedricziel/flysystem-gcs](https://github.com/cedricziel/flysystem-gcs) in a Laravel 5.x
compatible Service Provider.

**Note:**
This project doesn't support the deprecated `p12` credentials format anymore.
If you rely on it, please use the `1.x` versions.

## Configuration

*Dedicated credentials:* Obtain json service account credentials of a dedicated CloudPlatform Service Account

**or**

*Local authentication through gcloud:* Log in locally on your machine through the `gcloud` command-line
utility.

* Add the service provider to your application in ``config/app.php``
   ```php
   Websight\GcsProvider\CloudStorageServiceProvider::class,
   ```
   
* Add a disk to the `disks` array in config/filesystems.php
  ```php
  'gcs' => [
      // Select the Google Cloud Storage Disk
      'driver'         => 'gcs',
      // OPTIONAL: The location of the json service account certificate, see below
      // 'credentials' => storage_path('my-service-account-credentials.json'),
      // OPTIONAL: The GCP project id, see below
      // 'project_id'  => 'my-project-id-4711',
      // The bucket you want this disk to point at
      'bucket'         => 'my-project-id-4711.appspot.com',
  ],
  ```
   
* If Google Cloud Storage is the only `cloud` disk, you may consider
  setting it as the `cloud` disk, so that you can access it like
  `Storage::cloud()->$operation()` via `'cloud' => 'gcs',` in the `filesystems.php`
  config file.
   
## Authentication and the different configuration options

Google Cloud Platform uses json credential files. For the use-case of this library,
there are two different types that can easily confuse you.

1. credentials type `user`
   This is the type of credentials that identifies you as a user entity,
   most likely when authenticated through the `gcloud` utility.
   Since this type of credentials identifies users and users can belong
   to more than one project, you need to specify the `project_id` config option.
   The keys should automatically be detected through their well-known location.
2. credentials type `service_account`
   Service Account credentials are for authorizing machines and / or individual
   services to Google Cloud Platform. AppEngine instances and GCE machines
   already have a service account pre-installed so you don't need to configure
   neither `project_id` not `credentials`, since service accounts carry the information
   to which project they belong.

### When do I need to configure which option?

| Location                                | `project_id`             | `credentials`            | `bucket`        |
|-----------------------------------------|--------------------------|--------------------------|-----------------|
| AppEngine (Standard & Flex)             | *detected automatically* | *detected automatically* | needs to be set |
| Deployment to non-GCP machine           | needs to be set          | needs to be set          | needs to be set |
| Local development with user credentials | needs to be set          | *detected automatically* | needs to be set |
| Local development with service account  | *detected automatically* | needs to be set          | needs to be set |

## Usage

Use it like any other Flysystem Adapter with the ``Storage``-Facade.

```php
$disk = Storage::disk('gcs');

// Put a private file on the 'gcs' disk which is a Google Cloud Storage bucket
$disk->put('test.png', file_get_contents(storage_path('app/test.png')));

// Put a public-accessible file on the 'gcs' disk which is a Google Cloud Storage bucket
$disk->put(
    'test-public.png',
    file_get_contents(storage_path('app/test-public.png')),
    \Illuminate\Contracts\Filesystem\Filesystem::VISIBILITY_PUBLIC
);

// Retrieve a file
$file = $disk->get('test.png');
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
