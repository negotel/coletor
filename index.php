<?php
ob_start();
error_reporting(E_ALL & (~E_NOTICE | ~E_USER_NOTICE));
ini_set('error_log', 'php_erros.log');
ini_set('ignore_repeated_source', true);
ini_set('ignore_repeated_errors', true);
ini_set('display_errors', TRUE);
ini_set('log_errors', true);

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;
use Source\Core\Session;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/**
 * WEB ROUTES
 */
$route->group(null);
$route->get("/", "Web:login");
$route->get('/roda/cron', "Services:run");

//auth
$route->group(null);
$route->get("/entrar", "Web:login");
$route->post("/entrar", "Web:login");
$route->get("/cadastrar", "Web:register");
$route->post("/cadastrar", "Web:register");
$route->get("/recuperar", "Web:forget");
$route->post("/recuperar", "Web:forget");
$route->get("/recuperar/{code}", "Web:reset");
$route->post("/recuperar/resetar", "Web:reset");

//optin
$route->group(null);
$route->get("/confirma", "Web:confirm");
$route->get("/obrigado/{email}", "Web:success");

/**
 * APP
 */
$route->group("/app");
$route->get("/", "App:home");
$route->get("/importacao", "App:import");
$route->get("/criar/template/importacao", "App:create_template");
$route->get("/remessa/{remessa}", "App:remessa");
$route->get("/remessa/adicionar/{remessa}", "App:import");
$route->get("/lista/layout", "App:lista_layout");
$route->get("/remessa/print/{remessa}", "App:remessa_print");
$route->post("/processar/arquivo", "App:upload");
$route->post("/importacao/salva-layout", "App:save_layout");
$route->post("/importacao/excluir/layout", "App:delete_layout");

$route->get("/importacao/api", "App:importa_via_api");

$route->get("/perfil", "App:profile");
$route->get("/sair", "App:logout");

/**
 * ADMIN ROUTES
 */
$route->namespace("Source\App\Admin");
$route->group("/admin");

//login
$route->get("/", "Login:root");
$route->get("/login", "Login:login");
$route->post("/login", "Login:login");

//dash
$route->get("/dash", "Dash:dash");
$route->get("/dash/home", "Dash:home");
$route->post("/dash/home", "Dash:home");
$route->get("/logoff", "Dash:logoff");

//control
$route->get("/control/home", "Control:home");
$route->get("/control/subscriptions", "Control:subscriptions");
$route->post("/control/subscriptions", "Control:subscriptions");
$route->get("/control/subscriptions/{search}/{page}", "Control:subscriptions");
$route->get("/control/subscription/{id}", "Control:subscription");
$route->post("/control/subscription/{id}", "Control:subscription");
$route->get("/control/plans", "Control:plans");
$route->get("/control/plans/{page}", "Control:plans");
$route->get("/control/plan", "Control:plan");
$route->post("/control/plan", "Control:plan");
$route->get("/control/plan/{plan_id}", "Control:plan");
$route->post("/control/plan/{plan_id}", "Control:plan");

//blog
$route->get("/blog/home", "Blog:home");
$route->post("/blog/home", "Blog:home");
$route->get("/blog/home/{search}/{page}", "Blog:home");
$route->get("/blog/post", "Blog:post");
$route->post("/blog/post", "Blog:post");
$route->get("/blog/post/{post_id}", "Blog:post");
$route->post("/blog/post/{post_id}", "Blog:post");
$route->get("/blog/categories", "Blog:categories");
$route->get("/blog/categories/{page}", "Blog:categories");
$route->get("/blog/category", "Blog:category");
$route->post("/blog/category", "Blog:category");
$route->get("/blog/category/{category_id}", "Blog:category");
$route->post("/blog/category/{category_id}", "Blog:category");

//faqs
$route->get("/faq/home", "Faq:home");
$route->get("/faq/home/{page}", "Faq:home");
$route->get("/faq/channel", "Faq:channel");
$route->post("/faq/channel", "Faq:channel");
$route->get("/faq/channel/{channel_id}", "Faq:channel");
$route->post("/faq/channel/{channel_id}", "Faq:channel");
$route->get("/faq/question/{channel_id}", "Faq:question");
$route->post("/faq/question/{channel_id}", "Faq:question");
$route->get("/faq/question/{channel_id}/{question_id}", "Faq:question");
$route->post("/faq/question/{channel_id}/{question_id}", "Faq:question");

//users
$route->get("/users/home", "Users:home");
$route->post("/users/home", "Users:home");
$route->get("/users/home/{search}/{page}", "Users:home");
$route->get("/users/user", "Users:user");
$route->post("/users/user", "Users:user");
$route->get("/users/user/{user_id}", "Users:user");
$route->post("/users/user/{user_id}", "Users:user");

//notification center
$route->post("/notifications/count", "Notifications:count");
$route->post("/notifications/list", "Notifications:list");

//END ADMIN
$route->namespace("Source\App");

/**
 * PAY ROUTES
 */
$route->group("/pay");
$route->post("/create", "Pay:create");
$route->post("/update", "Pay:update");

/**
 * ERROR ROUTES
 */
$route->group("/ops");
$route->get("/{errcode}", "Web:error");

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();