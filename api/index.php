<?php
ob_start();

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');

require __DIR__ . "/../vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;

/**
 * API ROUTES
 * index
 */
$route = new Router(url(), ":");
$route->namespace("Source\App\Api");


/** 
 * API ROUTES
 */
//user
$route->group(null);
$route->get("/remessas/{type}", "Api:get_data_remessa");
$route->get("/remessa/coleta/{n_pedido}", "Api:coleta_remessa");
$route->get("/remessa/{remessa}", "Api:get_data_remessa_item");
$route->get("/remessa/coletados/{remessa}", "Api:get_objetos_coletado");
$route->get("/remessa/validar/{remessa}", "Api:validar_remessa");

$route->get("/notification/{type}", "Api:notification_send");
$route->get("/device/save/{pushNotificationID}", "Api:save_device");

$route->get("/check/update/vesion", "Api:check_update_version");


$route->get("/push", "api:push");


/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(404);

    echo json_encode([
        "errors" => [
            "type " => "endpoint_not_found",
            "message" => "Não foi possível processar a requisição"
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

ob_end_flush();
