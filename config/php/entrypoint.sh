#!/bin/bash
set -e

composer dump-autoload

exec "$@"
