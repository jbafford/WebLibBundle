<?php

namespace Bafford\WebLibBundle\Composer;

use Symfony\Component\Process\Process;

use Composer\Script\CommandEvent;

class WebLibInstall extends \Sensio\Bundle\DistributionBundle\Composer\ScriptHandler
{
    public static function installComponents(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];

        if (!is_dir($appDir)) {
            echo 'The symfony-app-dir ('.$appDir.') specified in composer.json was not found in '.getcwd().'.'.PHP_EOL;

            return;
        }

        static::executeCommand($event, $appDir, 'bafford:weblib:install');
    }
    
    public static function postInstall(CommandEvent $event)
    {
        return self::installComponents($event);
    }
    
    public static function postUpdate(CommandEvent $event)
    {
        return self::installComponents($event);
    }
}
