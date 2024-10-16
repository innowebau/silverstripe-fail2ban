<?php

namespace Innoweb\SilverstripeFail2Ban\Extensions;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Log\LoggerInterface;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Security\LoginAttempt;

class LoginAttemptExtension extends Extension
{
    private static $login_logfile = 'silverstripe-login.log';

    private function getLogger()
    {
        $logger = null;
        try {
            if (Environment::hasEnv('FAIL2BAN_LOGIN_LOGFILE')) {
                $logfile = Environment::getEnv('FAIL2BAN_LOGIN_LOGFILE');
            } else {
                $logfile = Config::inst()->get(self::class, 'login_logfile');
            }
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

    public function onAfterWrite(): void
    {
        if (($logger = $this->getLogger()) && $this->getOwner()->Status === LoginAttempt::FAILURE) {
            $logger->info(
                'Login Failure'
                . ' - host: ' . $this->getOwner()->IP
            );
        }
    }
}
