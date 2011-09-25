SyliusCatalogBundle documentation.
=====================================

Categorizing whatever you want just got easier.
You can use this bundle to create multiple categorized catalogs of any object.
It is still a prototype. Any contributions are welcome.

**Note!** This documentation is inspired by [FOSUserBundle docs](https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/index.md).

Installation.
-------------

+ Installing dependencies.
+ Downloading the bundle.
+ Autoloader configuration.
+ Adding bundle to kernel.
+ Importing routing cfgs.
+ Basic DIC configuration.
+ Creating your custom catalog.

### Installing dependencies.

This bundle uses Pagerfanta library and PagerfantaBundle.
The installation guide can be found [here](https://github.com/whiteoctober/WhiteOctoberPagerfantaBundle).

### Downloading the bundle.

The good practice is to download the bundle to the `vendor/bundles/Sylius/Bundle/CatalogBundle` directory.

This can be done in several ways, depending on your preference. The first
method is the standard Symfony2 method.

**Using the vendors script.**

Add the following lines in your `deps` file...

```
[SyliusAssortmentBundle]
    git=git://github.com/Sylius/SyliusCatalogBundle.git
    target=bundles/Sylius/Bundle/CatalogBundle
```

Now, run the vendors script to download the bundle.

``` bash
$ php bin/vendors install
```

**Using submodules.**

If you prefer instead to use git submodules, the run the following:

``` bash
$ git submodule add git://github.com/Sylius/SyliusCatalogBundle.git vendor/bundles/Sylius/Bundle/CatalogBundle
$ git submodule update --init
```

### Autoloader configuration.

Add the `Sylius\Bundle` namespace to your autoloader.

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Sylius\\Bundle' => __DIR__.'/../vendor/bundles',
));
```

### Adding bundle to kernel.

Finally, enable the bundle in the kernel.

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sylius\Bundle\CatalogBundle\SyliusCatalogBundle(),
    );
}
```
### Importing routing cfgs.

Now is the time to import routing files. Open up your `routing.yml` file. Customize the prefixes or whatever you want.

``` yaml
sylius_catalog_category:
    resource: "@SyliusCatalogBundle/Resources/config/routing/frontend/category.yml"

sylius_catalog_backend_category:
    resource: "@SyliusCatalogBundle/Resources/config/routing/backend/category.yml"
    prefix: /administration
```

### Basic DIC configuration.

This section will be written sometime, you can read about it [here](http://blog.diweb.pl/7/easy-categorizing-with-symfony2).

### Creating your custom catalog.

This section will be written sometime, you can read about it [here](http://blog.diweb.pl/7/easy-categorizing-with-symfony2).
