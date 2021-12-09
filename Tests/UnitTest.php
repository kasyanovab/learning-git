<?php

require_once __DIR__ . '/../Modules/Classes/AppConfig.php';
require_once __DIR__ . '/../Modules/Functions/appRun.php';

/**
 * Вычисляет расхождение массивов с дополнительной проверкой индекса. Поддержка многомерных массивов
 * @param array $a1
 * @param array $a2
 * @return array
 */
function array_diff_assoc_recursive(array $a1, array $a2): array
{
    $result = [];
    foreach ($a1 as $k1 => $v1) {
        if (false === array_key_exists($k1, $a2)) {
            $result[$k1] = $v1;
            continue;
        }

        if (is_iterable($v1) && is_iterable($a2[$k1])) {
            $resultCheck = array_diff_assoc_recursive($v1, $a2[$k1]);
            if (count($resultCheck) > 0) {
                $result[$k1] = $resultCheck;
            }
            continue;
        }

        if ($v1 !== $a2[$k1]) {
            $result[$k1] = $v1;
        }
    }
    return $result;
}

/**
 * Тестирование приложения
 */
class UnitTest
{
    /**
     * Провайдер данных для тестов
     * @return array
     */
    private static function testDataProvider(): array
    {
        $handlers = include __DIR__ . '/../Modules/Handlers/request.handlers.php';
        return [
            [
                'testName' => 'Тестирование поиска книг по названию',
                'in' => [
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    function () {
                    },
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../Env/dev.env.config.php');
                    }
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 10,
                            'title' => 'Мечтают ли андроиды об электроовцах?',
                            'year' => 1966,
                            'title_for_printing' => 'Название: Мечтают ли андроиды об электроовцах?. Автор: Дик Филип. Год: 1966.',
                            'author' =>
                                [
                                    'id' => 5,
                                    'name' => 'Филип',
                                    'surname' => 'Дик',
                                    'birthday' => '16.12.1928',
                                    'country' => 'us',
                                ],
                        ],
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные о книгах не корректны. Нет поля year',
                'in' => [
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    function () {
                    },
                    function () {
                        $config = include __DIR__ . '/../Env/dev.env.config.php';
                        $config['pathToBooks'] = __DIR__ . '/../Jsons/TestJsons/broken_books.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: year'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректным конфигом',
                'in' => [
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    function () {
                    },
                    static function () {
                        return "oops";
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный конфиг'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректным путем до файла с книгами',
                'in' => [
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    function () {
                    },
                    static function () {
                        $config = include __DIR__ . '/../Env/dev.env.config.php';
                        $config['pathToBooks'] = __DIR__ . '/../Jsons/TestJsons/unTitled.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректными данными о журналах. Нет поля id',
                'in' => [
                    $handlers,
                    '/books?title=National Geographic Magazine',
                    function () {
                    },
                    static function () {
                        $config = include __DIR__ . '/../Env/dev.env.config.php';
                        $config['pathToMagazines'] = __DIR__ . '/../Jsons/TestJsons/broken_magazines.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутсвуют обязательные элементы: id'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректными данными об авторе. Нет поля birthday',
                'in' => [
                    $handlers,
                    '/books?title=Мечтают ли андроиды об электроовцах?',
                    function () {
                    },
                    static function () {
                        $config = include __DIR__ . '/../Env/dev.env.config.php';
                        $config['pathToAuthors'] = __DIR__ . '/../Jsons/TestJsons/broken_authors.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутсвуют обязательные элементы: birthday'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректным путем с данными об авторе',
                'in' => [
                    $handlers,
                    '/books?title=National Geographic Magazine',
                    function () {
                    },
                    static function () {
                        $config = include __DIR__ . '/../Env/dev.env.config.php';
                        $config['pathToAuthors'] = __DIR__ . '/../Jsons/TestJsons/authors_fdf.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректным путем с данными о журнале',
                'in' => [
                    $handlers,
                    '/books?title=National Geographic Magazine',
                    function () {
                    },
                    static function () {
                        $config = include __DIR__ . '/../Env/dev.env.config.php';
                        $config['pathToMagazines'] = __DIR__ . '/../Jsons/TestJsons/magazine.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
        ];
    }

    /**
     * Запускает тесты
     * @return void
     */
    public static function runTests(): void
    {
        foreach (static::testDataProvider() as $testItem) {
            echo "----------" . $testItem['testName'] . "---------\n";
            $appResult = app(...$testItem['in']); // Распаковать в функцию массив

            //Assert
            if ($appResult['httpCode'] == $testItem['out']['httpCode']) {
                echo "    OK - код ответа\n";
            } else {
                echo "    Fail - код ответа. Ожидалось " . $testItem['out']['httpCode'] . ", а получил " . $appResult['httpCode'] . "\n";
            }

            $actualResult = json_decode(json_encode($appResult['result']), true);
            // Лишние элементы
            $unnecessaryElements = array_diff_assoc_recursive($actualResult, $testItem['out']['result']);
            // Недостающие элементы
            $missingElements = array_diff_assoc_recursive($testItem['out']['result'], $actualResult,);

            $errMsg = '';

            if (count($unnecessaryElements) > 0) {
                $errMsg .= sprintf("          Есть лишние элементы %s\n", json_encode($unnecessaryElements, JSON_UNESCAPED_UNICODE));
            }
            if (count($missingElements) > 0) {
                $errMsg .= sprintf("          Есть недостающие элементы %s\n", json_encode($missingElements, JSON_UNESCAPED_UNICODE));
            }

            if ('' === $errMsg) {
                echo "    OK - данные ответа валидны\n";
            } else {
                echo "    Fail - данные ответа валидны\n" . $errMsg;
            }
        }
    }
}

UnitTest::runTests();
