WebLibBundle
=============================

WebLibBundle bundle provides a simple way to copy or symlink locations in vendor into a web-accessible directory. This is useful when you need to use a javascript or css library that is not otherwise wrapped with a bundle.

##Installation

### Get the bundle

Add the following in your composer.json:

``` json
{
    "require": {
        "jbafford/web-lib-bundle": "dev-master"
    }
}
```

Or,

``` bash
./composer.phar require jbafford/web-lib-bundle dev-master
```

### Initialize the bundle

To start using the bundle, register the bundle in your application's kernel:

``` php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Bafford\WebLibBundle\BaffordWebLibBundle(),
    );
)
```

### Symfony2 configuration

Add the following configuration to your ``config.yml``:

``` yaml
bafford_web_lib:
    libdir: web/lib
    symlink: true
    contents:
        "path-in-vendor": "path-in-libdir"
        ...
```

The ``contents`` key is an array of paths within the vendor directory mapped to target destinations in the specified ``libdir``.


### Composer configuration

To run the WebLibBundle automatically after updating with Composer, add the following configuration to your ``composer.json``:

``` json
"scripts": {
    "post-install-cmd": [
    	...
        "Bafford\\WebLibBundle\\Composer\\WebLibInstall::postInstall"
    ],
    "post-update-cmd": [
    	...
        "Bafford\\WebLibBundle\\Composer\\WebLibInstall::postUpdate"
    ]
}
```

## Console command

WebLibBundle can also be run as a console command:

``` bash
./app/console bafford:weblib:install [--symlink] [--no-symlink]
```

When run with no options, WebLibBundle uses the configuration provided in your ``config.yml``. Alternatively, you can either force the use of symlinks with the ``--symlink`` option, or force it to copy files with ``--no-symlink``. (If both are given, symlinks are used.)