<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

// adding middleware to all requests to check the domain of the client
$app->add(function ($req, $res, $next) {
    if ($_SERVER['REMOTE_ADDR'] !== '::1') {
        // domain not allowed, return 403 Forbidden response
        return (new Slim\Http\Response())
		        ->withStatus(403)
		        ->withJson(['error' => 'access denied']);
    }

    return $next($req, $res);
});

use Tuupola\Middleware\HttpBasicAuthentication;
 
$container = $app->getContainer();
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

// $container["jwt"] = function ($container) {
//     return new StdClass;
// };

// $app->add(new \Slim\Middleware\JwtAuthentication([
//     "path" => "/",
//     "logger" => $container['logger'],
//     "secret" => "123456789helo_secret",
//     "rules" => [
//         new \Slim\Middleware\JwtAuthentication\RequestPathRule([
//             "path" => "/",
//             "passthrough" => ["/token", "/not-secure", "/home"]
//         ]),
//         new \Slim\Middleware\JwtAuthentication\RequestMethodRule([
//             "passthrough" => ["OPTIONS"]
//         ]),
//     ],
//     "callback" => function ($request, $response, $arguments) use ($container) {
//         $container["jwt"] = $arguments["decoded"];
//     },
//     "error" => function ($request, $response, $arguments) {
//         $data["status"] = "error";
//         $data["message"] = $arguments["message"];
//         return $response
//             ->withHeader("Content-Type", "application/json")
//             ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
//     }
// ]));

// $app->add(new \Slim\Middleware\HttpBasicAuthentication([
//     "path" => "/api/token",
//     "users" => [
//         "user" => "password"
//     ]
// ]));
    
// $app->add(function ($req, $res, $next) {
//     $response = $next($req, $res);
//     return $response
//             ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
//             ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//             ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
// });
    
// $app->add(new \Tuupola\Middleware\Cors([
//     // "logger" => $container["logger"],
//     "origin" => ["*"],
//     "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
//     "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],
//     "headers.expose" => ["Authorization", "Etag"],
//     "credentials" => true,
//     "cache" => 60,
//     "error" => function ($request, $response, $arguments) {
//         return new UnauthorizedResponse($arguments["message"], 401);
//     }
// ]));
