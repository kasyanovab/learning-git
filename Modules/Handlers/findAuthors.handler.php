<?php

include_once __DIR__ . "/../Functions/generalFunctions.php";

require_once __DIR__ . "/../Classes/AppConfig.php";
require_once __DIR__ . '/../Classes/Author.php';
require_once __DIR__ . '/../Classes/Book.php';

/**
 * Функция обработки поиска авторов
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @param AppConfig $appConfig - конфиг приложения
 * @return array
 * @throws JsonException
 */
return static function (array $request, callable $logger, AppConfig $appConfig): array {
    $logger("Ветка authors");
    $findAuthors = [];
    $authors = loadData($appConfig->getPathToAuthors());

    $paramsValidation = [
        'surname' => 'incorrect author surname',
    ];
    if (null === ($result = paramTypeValidation($paramsValidation, $request))) {
        foreach ($authors as $currentAuthor) {
            if (array_key_exists("surname", $request) && $request['surname'] === $currentAuthor['surname']) {
                $findAuthors[] = Author::createFromArray($currentAuthor);
            }
        }
        $logger("Найдено авторов : " . count($findAuthors));
        return [
            'httpCode' => 200,
            'result' => $findAuthors,
        ];
    }
    return $result;
};