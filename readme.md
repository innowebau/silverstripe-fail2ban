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

For Silverstripe, add the following config:

```yaml
Innoweb\SilverstripeFail2Ban\Extensions\LoginAttemptExtension:
  login_logfile: '/var/log/silverstripe-login.log'
```
Alternatively, you can set the log file location in your `.env` file:

```dotenv
FAIL2BAN_LOGIN_LOGFILE=/var/log/silverstripe-login.log
```
## configure Fail2ban jail

Add the Fail2ban filter to `/etc/fail2ban/filter.d/silverstripe-login.conf`:

```
# Fail2Ban filter for silverstripe logins

[INCLUDES]

before = common.conf

[Definition]

datepattern = \[%%Y-%%m-%%dT%%H:%%M:%%S.%%f%%z\]

failregex = ^ ss-fail2ban\.INFO: Login Failure - host: <HOST> - member: \S+ \[\] \[\]$

ignoreregex = 

# DEV Notes:
#
# pattern :     [2024-10-14T11:47:16.259637+11:00] ss-fail2ban.INFO: Login Failure - host: 127.0.0.1 - member: none [] []
#               [2024-10-14T11:47:16.259637+11:00] ss-fail2ban.INFO: Login Failure - host: 127.0.0.1 - member: email@host.tld [] []
```

Add the following to your `/etc/fail2ban/jail.local` to enable the filter:

```
[silverstripe-login]
enabled	= true
logpath = /var/log/silverstripe-login.log
```

## License

BSD 3-Clause License, see [License](license.md)
