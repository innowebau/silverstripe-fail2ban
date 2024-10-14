<?php

namespace Innoweb\SilverstripeFail2Ban\Extensions;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Log\LoggerInterface;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;

class LoginAttemptExtension extends Extension
{
    private static $login_logfile = 'silverstripe-login.log';

    public static function getLogger()
    {
        $logger = null;
        try {
            $logfile = Config::inst()->get(self::class, 'login_logfile');
            if (!str_starts_with('/', $logfile)) {
                $logfile = BASE_PATH . DIRECTORY_SEPARATOR . $logfile;
            }
            /* @var $logger LoggerInterface */
            $logger = Injector::inst()->get('Innoweb\SilverstripeFail2Ban\Logger');
            $logger->pushHandler(new StreamHandler($logfile, Level::Info));
        } catch (Exception $e) {
            /* no op */
        }
        return $logger;
    }

    public function onAfterWrite()
    {
        if ($logger = $this->getLogger()) {
            $logger->info(
                'Login ' . $this->getOwner()->Status
                . ' - host: ' . $this->getOwner()->IP
                . ' - member: ' . ((($member = $this->getOwner()->Member()) && $member->exists()) ? $member->Email : 'none')
            );
        }
    }
}
