ServicesBundle
==============

ServicesBundle does the following:

- Defines a set of services used by c975L bundles

[ServicesBundle dedicated web page](https://975l.com/en/pages/services-bundle).

[ServicesBundle API documentation](https://975l.com/apidoc/c975L/ServicesBundle.html).

Bundle installation
===================

Step 1: Download the Bundle
---------------------------
Use [Composer](https://getcomposer.org) to install the library
```bash
    composer require c975l/services-bundle
```

Step 2: Enable the Bundle
-------------------------
Then, enable the bundle by adding it to the list of registered bundles in the `app/AppKernel.php` file of your project:

```php
<?php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new c975L\ServicesBundle\c975LServicesBundle(),
        ];
    }
}
```

How to use
----------
Call the needed service via its interface and use its methods.