CocoricoSwiftReaderBundle
=========================

CocoricoSwiftReaderBundle is a free Symfony bundle to read emails sent by 
[Swift Mailer](https://github.com/swiftmailer/swiftmailer) from the Symfony Web Debug Toolbar.

Installation
------------

### Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```bash
$ composer require-dev cocorico/swift-reader-bundle
```

### Enable the Bundle

Then, enable the bundle by adding the following line in the `app/config/AppKernel.php` file of your project:

```php
...
    if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
        ...
        $bundles[] = new Cocorico\SwiftReaderBundle\CocoricoSwiftReaderBundle();
    }
...
```

### Update the routing

Add the main route by adding the following lines in the `app/config/routing_dev.yml` file of your project:

```yaml
...
_cocorico_swift_reader:
    resource: "@CocoricoSwiftReaderBundle/Resources/config/routing.yml"
    prefix:   /_cocorico_swift_reader
```

Configuration
-----

It is possible to modify the path where the emails are stored by adding the following lines in the `app/config/config_dev.yml` file of your project:

```yaml
...
cocorico_swift_reader:
    path: "your/path/here"
```

Usage
-----

One you have installed the bundle, you can read emails by clicking on the link `Swift Reader` in the profiler toolbar. 

License
-------

This bundle is under the MIT license. See the complete license [in the bundle](LICENSE).


About Us
--------

This bundle is sponsored by [Cocolabs][1] creator of [Cocorico][2] an open source marketplace solution for services and 
rentals.

[1]: http://www.cocolabs.io
[2]: https://github.com/Cocolabs-SAS/cocorico