<?php

namespace Bafford\WebLibBundle\Composer;

use Symfony\Component\Process\Process;

use Composer\Script\Event;

class WebLibInstall extends \Sensio\Bundle\DistributionBundle\Composer\ScriptHandler
{
    public static function installComponents(Event $event)
    {
        $options = self::getOptions($event);
        
        $consoleDir = static::getConsoleDir($event, 'execute command');
        if(!$consoleDir) {
            return;
        }

        static::executeCommand($event, $consoleDir, 'bafford:weblib:install');
    }
    
    public static function postInstall(Event $event)
    {
        return self::installComponents($event);
    }
    
    public static function postUpdate(Event $event)
    {
        return self::installComponents($event);
    }
}
