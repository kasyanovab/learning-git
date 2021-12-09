<?php

/**
 * Конфиг приложения
 */
final class AppConfig
{
    /**
     * Путь до файла с данными об авторе
     * @var string
     */
    private string $pathToAuthors = __DIR__ . "/../../Jsons/TestJsons/authors.json";

    /**
     * Путь до файла с данными о книгах
     * @var string
     */
    private string $pathToBooks = __DIR__ . "/../../Jsons/TestJsons/books.json";

    /**
     * Путь до файла с данными о журналах
     * @var string
     */
    private string $pathToMagazines = __DIR__ . "/../../Jsons/TestJsons/magazines.json";

    /**
     * Получить путь до файла с данными об авторе
     * @return string
     */
    public function getPathToAuthors(): string
    {
        return $this->pathToAuthors;
    }

    /**
     * Установить путь до файла с данными об авторе
     * @param string $pathToAuthors
     * @return AppConfig
     * @throws Exception - не найден файл
     */
    public function setPathToAuthors(string $pathToAuthors): AppConfig
    {
        $this->validateFilePath($pathToAuthors);
        $this->pathToAuthors = $pathToAuthors;
        return $this;
    }

    /**
     * Получить путь до файла с данными о книгах
     * @return string
     */
    public function getPathToBooks(): string
    {
        return $this->pathToBooks;
    }

    /**
     * Установить путь до файла с данными о книгах
     * @param string $pathToBooks
     * @return AppConfig
     * @throws Exception - не найден файл
     */
    public function setPathToBooks(string $pathToBooks): AppConfig
    {
        $this->validateFilePath($pathToBooks);
        $this->pathToBooks = $pathToBooks;
        return $this;
    }

    /**
     * Получить путь до файла с данными о журналах
     * @return string
     */
    public function getPathToMagazines(): string
    {
        return $this->pathToMagazines;
    }

    /**
     * Установить путь до файла с данными о журналах
     * @param string $pathToMagazines
     * @return AppConfig
     * @throws Exception - не найден файл
     */
    public function setPathToMagazines(string $pathToMagazines): AppConfig
    {
        $this->validateFilePath($pathToMagazines);
        $this->pathToMagazines = $pathToMagazines;
        return $this;
    }

    /**
     * Валидация пути до файла
     * @param string $path
     * @return void
     * @throws Exception - если файл не найден
     */
    private function validateFilePath(string $path): void
    {
        if (false === file_exists($path)) {
            throw new Exception('Некорректный путь до файла с данными');
        }
    }

    /**
     * Создает конфиг приложения из массива
     * @param array $config
     * @return static
     * @uses AppConfig::setPathToMagazines()
     * @uses \AppConfig::setPathToAuthors()
     * @uses \AppConfig::setPathToBooks()
     */
    public static function createFromArray(array $config): self
    {
        $appConfig = new self();
        foreach ($config as $key => $value) {
            if (property_exists($appConfig, $key)) {
                $setter = 'set' . ucfirst($key);
                $appConfig->{$setter}($value);
            }
        }
        return $appConfig;
    }
}