<?php

require_once __DIR__ . '/../Functions/appRun.php';

require_once __DIR__ . '/../Classes/Author.php';
require_once __DIR__ . '/../Classes/Book.php';
require_once __DIR__ . '/../Classes/Magazine.php';
require_once __DIR__."/../Classes/AppConfig.php";

/**
 * Обработка запроса /books
 * @param array $request - параметры, которые передает пользователь
 * @param callable $logger - функция, инкапсулирующая логку логирования
 * @param AppConfig $appConfig - конфиг приложения
 * @return array
 * @throws JsonException
 */
return static function (array $request, callable $logger, AppConfig $appConfig): array {
    $logger("Ветка books");
    $authors = loadData($appConfig->getPathToAuthors());
    $books = loadData($appConfig->getPathToBooks());
    $magazines = loadData($appConfig->getPathToMagazines());

    $paramTypeValidation = [
        'author_surname' => "incorrect author surname",
        'title' => 'incorrect book title',
    ];

    $books = array_merge($books, $magazines);

    if (null === ($result = paramTypeValidation($paramTypeValidation, $request))) {
        $authorIdToInfo = [];
        $findBooks = [];
        foreach ($authors as $author) {
            $authorIdToInfo[$author['id']] = Author::createFromArray($author);
        }

        foreach ($books as $book) {
            if (array_key_exists("author_surname", $request)) {
                $bookMeetSearchCriteria = null !== $book['author_id'] && $authorIdToInfo[$book["author_id"]]->getSurname() === $request["author_surname"];
            } else {
                $bookMeetSearchCriteria = true;
            }
            if ($bookMeetSearchCriteria && array_key_exists("title", $request)) {
                $bookMeetSearchCriteria = $request["title"] === $book["title"];
            }

            if ($bookMeetSearchCriteria) {
                $book['author'] = null === $book['author_id'] ? null : $authorIdToInfo[$book['author_id']];
                if (array_key_exists('number', $book)) {
                    $bookObj = Magazine::createFromArray($book);
                } else {
                    $bookObj = Book::createFromArray($book);
                }
                $findBooks[] = $bookObj;
            }
        }
        $logger("Найдено книг: " . count($findBooks));

        $result = [
            'httpCode' => 200,
            'result' => $findBooks,
        ];
    }
    return $result;
};