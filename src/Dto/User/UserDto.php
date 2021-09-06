<?php
namespace App\Dto\User;

use App\Dto\AbstractDto;

class UserDto extends AbstractDto
{
    private ?int $id = null;

    private ?string $name = null;

    private ?string $email = null;

    private ?\DateTime $created = null;

    private ?\DateTime $deleted = null;

    private ?string $notes = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return UserDto
     */
    public function setId(?int $id): UserDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return UserDto
     */
    public function setName(?string $name): UserDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return UserDto
     */
    public function setEmail(?string $email): UserDto
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime|null $created
     * @return UserDto
     */
    public function setCreated(?\DateTime $created): UserDto
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeleted(): ?\DateTime
    {
        return $this->deleted;
    }

    /**
     * @param \DateTime|null $deleted
     * @return UserDto
     */
    public function setDeleted(?\DateTime $deleted): UserDto
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @param string|null $notes
     * @return UserDto
     */
    public function setNotes(?string $notes): UserDto
    {
        $this->notes = $notes;
        return $this;
    }

}
