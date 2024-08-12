<?php
declare(strict_types=1);

namespace WHMCS\Module\Server\AzuraCast\Dto;

use JsonSerializable;

class UserDto implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $newPassword;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var int
     */
    protected $createdAt;

    /**
     * @var int
     */
    protected $updatedAt;

    /**
     * @var RoleDto[]
     */
    protected $roles;

    /**
     * @param int $id
     * @param string $email
     * @param string $name
     * @param string $locale
     * @param int $createdAt
     * @param int $updatedAt
     * @param RoleDto[] $roles
     */
    public function __construct(
        int $id,
        string $email,
        string $name,
        string $locale,
        int $createdAt,
        int $updatedAt,
        array $roles
    ) {
        $this->setId($id)
            ->setEmail($email)
            ->setNewPassword('')
            ->setName($name)
            ->setLocale($locale)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt)
            ->setRoles($roles);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return UserDto
     */
    public function setId(int $id): UserDto
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return UserDto
     */
    public function setEmail(string $email): UserDto
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     *
     * @return UserDto
     */
    public function setNewPassword(string $newPassword): UserDto
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return UserDto
     */
    public function setName(string $name): UserDto
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     *
     * @return UserDto
     */
    public function setLocale(string $locale): UserDto
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     *
     * @return UserDto
     */
    public function setCreatedAt(int $createdAt): UserDto
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    /**
     * @param int $updatedAt
     *
     * @return UserDto
     */
    public function setUpdatedAt(int $updatedAt): UserDto
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return RoleDto[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param RoleDto[] $roles
     *
     * @return UserDto
     */
    public function setRoles(array $roles): UserDto
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param RoleDto $role
     *
     * @return UserDto
     */
    public function addRole(RoleDto $role): UserDto
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'new_password' => $this->getNewPassword(),
            'name' => $this->getName(),
            'locale' => $this->getLocale(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'roles' => $this->getRoles(),
        ];
    }

    /**
     * @param mixed[] $userData
     *
     * @return UserDto
     */
    public static function fromArray(array $userData): self
    {
        $roles = [];
        foreach ($userData['roles'] as $roleData) {
            $roles[] = RoleDto::fromArray($roleData);
        }

        return new self(
            $userData['id'],
            $userData['email'],
            $userData['name'] ?? '',
            $userData['locale'] ?? '',
            $userData['created_at'],
            $userData['updated_at'],
            $roles,
        );
    }
}
