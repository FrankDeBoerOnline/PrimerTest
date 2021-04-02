<?php

if(file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';

} else if(file_exists('/var/www/vendor/autoload.php')) {
    require '/var/www/vendor/autoload.php';

} else {
    error_log("Wrong composer configuration");
    exit("Something is wrong with this website");
}

require __DIR__ . '/.config/config.php';
