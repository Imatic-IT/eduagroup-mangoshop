#!/usr/bin/env bash
set -euo pipefail

[[ -d tools ]] || mkdir tools
[[ -f tools/composer.phar ]] || { curl https://getcomposer.org/installer | php && mv composer.phar tools/composer.phar; }
