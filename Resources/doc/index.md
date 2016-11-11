WebLibBundle
=============================

WebLibBundle bundle provides a simple way to copy or symlink files or directories into a web-accessible directory. This is useful when you need to use a javascript or css library that is not otherwise wrapped with a bundle.

##Installation

### Get the bundle

Add the following in your composer.json:

``` json
{
    "require": {
        "jbafford/web-lib-bundle": "~1.0"
    }
}
```

Or,

``` bash
./composer.phar require jbafford/web-lib-bundle ~1.0
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

### Symfony configuration

Add the following configuration to your ``config.yml``:

``` yaml
bafford_web_lib:
    target_dir: "web/lib"
    symlink: true
    contents:
        -
            source: "path in source directory"
            destination: "path in target_dir"
            files: [ optional list of files names to copy ]
```

* ``target_dir`` is a path relative to your project root that serves as a base path for copied content
* ``symlink``, when ``true``, uses symlinks; when ``false``, files are copied
* ``contents`` is an array of entries that describe what to copy to the ``target_dir``:
	* ``source`` is a filesystem path relative to your project root
	* ``destination`` is a filesystem path relative to ``target_dir``
	* ``files`` is an optional array which contains a list of items in ``source`` to copy or symlink into ``destination``. If omitted, the entire ``source`` is copied or symlinked.


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