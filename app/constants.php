<?php

/*
|--------------------------------------------------------------------------
| Application Constant
|--------------------------------------------------------------------------
|
| Just add your constant value in this sections or from constants folder
| and you can used it right away.
|
| @package System Core / constants.php
| @author  Anthony Pillos <dev.anthonypillos@gmail.com>
| @version v1
*/

$env = App::environment();

foreach (glob(__DIR__ . '/constants/*.php') as $route_file)
  require $route_file;