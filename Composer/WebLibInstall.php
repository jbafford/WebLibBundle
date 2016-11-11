<?php

namespace Bafford\WebLibBundle\Composer;

use Symfony\Component\Process\Process;

use Composer\Script\CommandEvent;

class WebLibInstall extends \Sensio\Bundle\DistributionBundle\Composer\ScriptHandler
{
    public static function installComponents(CommandEvent $event)
    {
        $options = self::getOptions($event);
        
        $consoleDir = static::getConsoleDir($event, 'execute command');
        if(!$consoleDir) {
            return;
        }

        static::executeCommand($event, $consoleDir, 'bafford:weblib:install');
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
