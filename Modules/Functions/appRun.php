<?php

require_once __DIR__ . "/../../Modules/Classes/AppConfig.php";
require_once __DIR__ . "/../../Modules/Classes/InvalidDataStructureException.php";

/**
 * Реализация веб приложения
 * @param array $handlers
 * @param string $requestUri - uri запроса
 * @param callable logger - функция, инкапсулирующая логику логгера
 * @param callable $appConfigFactory - фабрика, реализующая логику создания конфига приложения
 * @return array
 */
function app(array $handlers, string $requestUri, callable $logger, callable $appConfigFactory): array
{
    try {
        $query = parse_url($requestUri, PHP_URL_QUERY);
        $requestParams =[];
        parse_str($query, $requestParams);

        $appConfig = $appConfigFactory();
        if (!($appConfig instanceof AppConfig)) {
            throw new Exception("Некорректный конфиг");
        }
        $urlPath = parse_url($requestUri, PHP_URL_PATH);
        $logger("Переход на " . urldecode($requestUri));

        if (array_key_exists($urlPath, $handlers)) {
            $result = $handlers[$urlPath]($requestParams, $logger, $appConfig);
        } else {
            $result = [
                'httpCode' => 404,
                'result' => [
                    'status' => 'fail',
                    'message' => 'unsupported request'
                ]
            ];
        }
    } catch (invalidDataStructureException $e) {
        $result = [
            'httpCode' => 503,
            'result' => [
                'status' => 'fail',
                'message' => $e->getMessage()
            ]
        ];
    } catch (Throwable $e) {
        $result = [
            'httpCode' => 500,
            'result' => [
                'status' => 'fail',
                'message' => $e->getMessage()
            ],
        ];
    }
    return $result;
}





