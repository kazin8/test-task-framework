<?php
namespace App\Dto\Journal;

use App\Dto\AbstractDto;
use Exception;

class JournalDto extends AbstractDto implements \JsonSerializable
{
    private string $type;

    private array $content;

    private int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return JournalDto
     */
    public function setId(int $id): JournalDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return JournalDto
     */
    public function setType(string $type): JournalDto
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param array $content
     * @return JournalDto
     */
    public function setContent(array $content): JournalDto
    {
        $this->content = $content;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'content' => $this->getContent()
        ];
    }
}
