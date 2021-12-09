<?php

/**
 * Общие свойства для всех подмножеств наслдеников (Книга, Журнал)
 */
abstract class AbstractTextDocument implements JsonSerializable
{
    /**
     * Год издания книги
     *
     * @var int
     */
    private int $year;

    /**
     * id книги
     *
     * @var int
     */
    private int $id;

    /**
     * название книги
     *
     * @var string
     */
    private string $title;

    /**
     * @param int $id - id документа
     * @param string $title - название
     * @param int $year - год
     */
    public function __construct(int $id, string $title, int $year)
    {
        $this->year = $year;
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * Получить название книги
     *
     * @return string
     */
    final public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Получить год создания книги
     *
     * @return int
     */
    final public function getYear(): int
    {
        return $this->year;
    }

    /**
     * Получить id книги
     *
     * @return int
     */
    final public function getId(): int
    {
        return $this->id;
    }

    abstract public function getTitleForPrinting();

    /**
     * Данные о печатном документе -  для сериализации в json
     * @return mixed|void
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'title_for_printing' => $this->getTitleForPrinting(),
        ];
    }

    /**
     * Создает сущность из массива
     * @param array $data
     * @return mixed
     */
    abstract public static function createFromArray(array $data): AbstractTextDocument;
}