<?php

require_once __DIR__ . "/AbstractTextDocument.php";
require_once __DIR__ . "/InvalidDataStructureException.php";

/**
 * Автор
 */
final class Author implements JsonSerializable
{

    /**
     * id автора
     * @var int
     */
    private int $id;

    /**
     * Имя автора
     * @var string
     */
    private string $name;

    /**
     * Фамилия автора
     * @var string
     */
    private string $surname;

    /**
     * Дата рождения автора
     * @var string
     */
    private string $birthday;

    /**
     * Страна автора
     * @var string
     */
    private string $country;

    /**
     * @param int $id - идентификатор автора
     * @param string $name - имя автора
     * @param string $surname - фамилия автора
     * @param string $birthday - дата рождения автора
     * @param string $country - страна
     */
    public function __construct(int $id, string $name, string $surname, string $birthday, string $country)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->birthday = $birthday;
        $this->country = $country;
    }

    /**
     * Получить id автора
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить имя автора
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Получить фамилию автора
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * Получить дату рождения
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * Получить страну автора
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Реализует функцию преобразования данных в json
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'birthday' => $this->birthday,
            'country' => $this->country
        ];
    }

    /**
     * Создание объекта из массива
     * @param array $data - данные для маппинга
     * @return Author
     */
    public static function createFromArray(array $data): Author
    {
        $requiredFields = [
            'id',
            'name',
            'surname',
            'birthday',
            'country'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутсвуют обязательные элементы: %s', implode(',', $missingFields));
            throw new invalidDataStructureException($errMsg);
        }

        return new Author($data['id'], $data['name'], $data['surname'], $data['birthday'], $data['country']);
    }

}