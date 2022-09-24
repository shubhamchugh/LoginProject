<?php

echo "<h2>Cache Clear Update Output</h2>";
$cache_clear = shell_exec('cd .. && php artisan cache:clear');
$cache_truncate = shell_exec('cd .. && php artisan cache:truncate');
$clear_compiled = shell_exec('cd .. && php artisan clear-compiled');
$view_clear = shell_exec('cd .. && php artisan view:clear');
$route_clear = shell_exec('cd .. && php artisan route:clear');
$optimize_clear = shell_exec('cd .. && php artisan optimize:clear');
$event_clear = shell_exec('cd .. && php artisan event:clear');
$config_clear = shell_exec('cd .. && php artisan config:clear');

echo $cache_clear;
echo $cache_truncate;
echo $clear_compiled;
echo $view_clear;
echo $route_clear;
echo $optimize_clear;
echo $event_clear;
echo $config_clear;

echo '<strong>Last Reboot: </strong>' . shell_exec('who -b');