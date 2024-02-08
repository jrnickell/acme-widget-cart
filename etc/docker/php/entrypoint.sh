#!/usr/bin/env bash

# start fpm and tail the log file
/bin/sh -c php-fpm --pid /usr/local/var/run/php-fpm.pid | tail -f /tmp/stdout
