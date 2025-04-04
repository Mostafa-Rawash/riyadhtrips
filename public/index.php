<?php
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
if(!empty($_SERVER['REQUEST_URI'])){
	if(strpos($_SERVER['REQUEST_URI'],'/install') !== false){
		if(!file_exists(__DIR__.'/../.env')){
			copy(__DIR__.'/../.env.example',__DIR__.'/../.env');
		}
	}
}
if (!version_compare(phpversion(), '8.0.2', '>'))
{
    die("Current PHP version: ".phpversion()."<br>You must upgrade PHP version 8.0.2 and later");
}
if(file_exists(__DIR__.'/../storage/bc.php'))
{
    require __DIR__.'/../storage/bc.php';
}
if (file_exists(__DIR__ . '/../storage/pro.php')) {
    require __DIR__ . '/../storage/pro.php';
}
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';
// set the public path to this directory
$app->bind('path.public', function () {
    return __DIR__;
});
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
