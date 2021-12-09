<?php

require_once __DIR__ . "/Modules/Functions/appRun.php";
require_once __DIR__ . "/Modules/Functions/generalFunctions.php";
require_once __DIR__ . "/Modules/Classes/AppConfig.php";

$returnApp = app(
    include __DIR__ . "/Modules/Handlers/request.handlers.php",
    $_SERVER['REQUEST_URI'],
    'loggerInFile',
    static function () {
        return AppConfig::createFromArray(include __DIR__ . "/Env/dev.env.config.php");
    }
);
try {
    render($returnApp['httpCode'], $returnApp['result']);
} catch (JsonException $e) {
    var_dump($e);
}
