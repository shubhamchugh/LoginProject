<?php


echo "<h2>Cache Create Output</h2>";
$view_cache = shell_exec('cd .. && php artisan view:cache');
$route_cache = shell_exec('cd .. && php artisan route:cache');
$event_cache = shell_exec('cd .. && php artisan event:cache');
$config_cache = shell_exec('cd .. && php artisan config:cache');


echo $view_cache;
echo $route_cache;
echo $event_cache;
echo $config_cache;

echo '<strong>Last Reboot: </strong>' . shell_exec('who -b');