<?php

require_once __DIR__ . '/InvalidDataStructureException.php';

/**
 * Книга
 */
final class Book extends AbstractTextDocument
{
    /**
     * @inheritDoc
     * @param Author $author
     */
    public function __construct(int $id, string $title, int $year, Author $author)
    {
        parent::__construct($id, $title, $year);
        $this->author = $author;
    }

    /**
     * Возвращает автора книги
     * @return Author
     */
    public function getAuthor(): Author
    {
        return $this->author;
    }

    /**
     * Author книги
     * @var Author
     */
    private Author $author;

    /**
     * Выводит заголовок для печати
     * @return string
     */
    public function getTitleForPrinting(): string
    {
        return "Название: {$this->getTitle()}. Автор: {$this->getAuthor()->getSurname()} {$this->getAuthor()->getName()}. Год: {$this->getYear()}.";
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['author'] = $this->author;
        return $jsonData;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public static function createFromArray(array $data): Book
    {
        $requiredFields = [
            'id',
            'title',
            'year',
            'author'
        ];
        // array_keys return keys of array
        $missingFields = array_diff($requiredFields, array_keys($data));
        if (count($missingFields) > 0) {
            // implode объединяет элементы массива и выводит их через запятую.
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new invalidDataStructureException($errMsg);
        }
        return new Book($data['id'], $data['title'], $data['year'], $data['author']);
    }
}