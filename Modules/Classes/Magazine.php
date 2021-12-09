<?php

require_once __DIR__ . "/AbstractTextDocument.php";
require_once __DIR__ . "/InvalidDataStructureException.php";

/**
 * Журнал
 */
final class Magazine extends AbstractTextDocument
{
    /**
     * @inheritDoc
     * @param int $number
     */
    public function __construct(int $id, string $title, int $year, int $number, ?Author $author)
    {
        parent::__construct($id, $title, $year);
        $this->author = $author;
        $this->number = $number;
    }

    /**
     * Author книги
     * @var Author|null
     */
    private ?Author $author;

    /**
     * Получить автора журнала
     * @return Author|null
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * Получить номер журнала
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Номер журнала
     * @var int
     */
    private int $number;

    /**
     * Выводит заголовок для печати
     * @return string
     */
    public function getTitleForPrinting(): string
    {
        return "Название: {$this->getTitle()}. Год: {$this->getYear()}. Номер: {$this->getNumber()}.";
    }

    public function jsonSerialize()
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['author'] = $this->author;
        $jsonData['number'] = $this->number;
        return $jsonData;
    }

    /**
     * Создание объекта из массива
     * @throws Exception
     */
    public static function createFromArray(array $data): Magazine
    {
        $requiredFields = [
            'id',
            'title',
            'year',
            'number',
            'author'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутсвуют обязательные элементы: %s', implode(',', $missingFields));
            throw new invalidDataStructureException($errMsg);
        }

        return new Magazine($data['id'], $data['title'], $data['year'], $data['number'], $data['author']);
    }
}
