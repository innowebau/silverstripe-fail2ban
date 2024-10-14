# Silverstripe login Fail2ban integration

[![Version](http://img.shields.io/packagist/v/innoweb/silverstripe-fail2ban.svg?style=flat-square)](https://packagist.org/packages/innoweb/silverstripe-fail2ban)
[![License](http://img.shields.io/packagist/l/innoweb/silverstripe-fail2ban.svg?style=flat-square)](license.md)

## Overview

Logs failed logins into a separate log file to be consumed by Fail2ban to block IPs with failed logins

## Requirements

* Silverstripe Framework 5.x

## Installation

Install the module using composer:

```bash
composer require innoweb/silverstripe-fail2ban
```

Then run dev/build.

### configure Silverstripe log file location

By default, the login attempts are logged to `silverstripe-login.log` in the base folder of your project.

You can change the log file location by adding the following Silverstripe config:

```yaml
Innoweb\SilverstripeFail2Ban\Extensions\LoginAttemptExtension:
  login_logfile: 'your/path/to/silverstripe-login.log'
```

Alternatively, you can set the log file location in your `.env` file:

```dotenv
FAIL2BAN_LOGIN_LOGFILE=your/path/to/silverstripe-login.log
```

### configure Fail2ban jail

Add the Fail2ban filter to `/etc/fail2ban/filter.d/silverstripe-login.conf`:

```
# Fail2Ban filter for silverstripe logins

[INCLUDES]

before = common.conf

[Definition]

datepattern = \[%%Y-%%m-%%dT%%H:%%M:%%S.%%f%%z\]

failregex = ^ ss-fail2ban\.INFO: Login Failure - host: <HOST> \[\] \[\]$

ignoreregex = 

# DEV Notes:
#
# pattern :     [2024-10-14T11:47:16.259637+11:00] ss-fail2ban.INFO: Login Failure - host: 127.0.0.1 [] []
#               [2024-10-14T11:47:16.259637+11:00] ss-fail2ban.INFO: Login Failure - host: 127.0.0.1 [] []
```

Add the following to your `/etc/fail2ban/jail.local` to enable the filter:

```
[silverstripe-login]
enabled	= true
logpath = /your/path/to/silverstripe-login.log
```

## License

BSD 3-Clause License, see [License](license.md)
