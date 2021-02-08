<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// PDO database library
$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'].";charset=UTF8",
        $settings['user'], $settings['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

//DIR upload file
$container['upload_directory'] = function ($c) {
    return __DIR__ . '/uploads';
};

// controller
$container['ChatController'] = function($c) {
    return new Controllers\ChatController($c['db']);
};
$container['ChatManageController'] = function($c) {
    return new Controllers\ChatManageController($c['db']);
};
$container['ChatThemeController'] = function($c) {
    return new Controllers\ChatThemeController($c['db']);
};
$container['ChatUserController'] = function($c) {
    return new Controllers\ChatUserController($c['db']);
};
$container['CompanyController'] = function($c) {
    return new Controllers\CompanyController($c['db']);
};
$container['CentersStaffController'] = function($c) {
    return new Controllers\CentersStaffController($c['db']);
};
$container['ChatHistoryController'] = function($c) {
    return new Controllers\ChatHistoryController($c['db']);
};
$container['FixedPhraseManageController'] = function($c) {
    return new Controllers\FixedPhraseManageController($c['db']);
};
$container['AnalyticController'] = function($c) {
    return new Controllers\AnalyticController($c['db']);
};
$container['FaqController'] = function($c) {
    return new Controllers\FaqController($c['db']);
};