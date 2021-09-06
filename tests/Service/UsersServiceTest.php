<?php

namespace App\Tests\Service;

use App\Dto\User\UserDto;
use App\Exception\EntityNotFoundException;
use App\Service\User\UserServiceInterface;

class UsersServiceTest extends AbstractServiceTest
{
    /** @var UserServiceInterface */
    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        /** @var UserServiceInterface $userService */
        $this->userService = $container->get(UserServiceInterface::class);
    }

    public function testCreateValidUser()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());
        $this->assertEquals('example123', $userDto->getName());
        $this->assertEquals('email@email.com', $userDto->getEmail());
        $this->assertLessThanOrEqual((new \DateTimeImmutable()), $userDto->getCreated());
        $this->assertNull($userDto->getDeleted());
        $this->assertEquals('Some text here', $userDto->getNotes());
    }

    public function testCreateUserWithInvalidName()
    {
        $dto = new UserDto();
        $dto->setName('fail_1')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(true, $userDto->isError());

        $this->assertEquals(2, $userDto->getErrorsCount());

        $error = $userDto->getErrors()->get(0);
        $this->assertEquals('Your name must be at least 8 characters long', $error->getMessage());

        $error = $userDto->getErrors()->get(1);
        $this->assertEquals('Your name can contain only letters and numbers', $error->getMessage());

        $this->assertNull($userDto->getId());
    }

    public function testCreateUserWithInvalidEmail()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('example.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(true, $userDto->isError());

        $this->assertEquals(1, $userDto->getErrorsCount());

        $error = $userDto->getErrors()->get(0);
        $this->assertEquals('The email "example.com" is not a valid email.', $error->getMessage());

        $this->assertNull($userDto->getId());
    }

    public function testCreateTwoUsers()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());
        $this->assertEquals('example123', $userDto->getName());
        $this->assertEquals('email@email.com', $userDto->getEmail());
        $this->assertLessThanOrEqual((new \DateTimeImmutable()), $userDto->getCreated());
        $this->assertNull($userDto->getDeleted());
        $this->assertEquals('Some text here', $userDto->getNotes());

        $dto = new UserDto();
        $dto->setName('example1234')
            ->setEmail('email2@email.com')
            ->setNotes('Some new text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(2, $userDto->getId());
        $this->assertEquals('example1234', $userDto->getName());
        $this->assertEquals('email2@email.com', $userDto->getEmail());
        $this->assertLessThanOrEqual((new \DateTimeImmutable()), $userDto->getCreated());
        $this->assertNull($userDto->getDeleted());
        $this->assertEquals('Some new text here', $userDto->getNotes());
    }

    public function testCreateTwoUsersWithSameNames()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('first@example.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(1, $userDto->getId());

        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('second@example.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(true, $userDto->isError());

        $this->assertEquals(1, $userDto->getErrorsCount());

        $error = $userDto->getErrors()->get(0);
        $this->assertEquals('The name "example123" already in use', $error->getMessage());

        $this->assertNull($userDto->getId());
    }

    public function testGetValidUser()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());

        $getUserDto = $this->userService->get($userDto->getId());

        $this->assertEquals(false, $getUserDto->isError());
        $this->assertEquals(1, $getUserDto->getId());
        $this->assertEquals('example123', $getUserDto->getName());
        $this->assertEquals('email@email.com', $getUserDto->getEmail());
        $this->assertNotNull($getUserDto->getCreated());
        $this->assertNull($getUserDto->getDeleted());
        $this->assertEquals('Some text here', $getUserDto->getNotes());
    }

    public function testGetNotExistsUser()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Entity not found');

        $userDto = $this->userService->get(1);
    }

    public function testUpdateValidUser()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());
        $this->assertEquals('example123', $userDto->getName());
        $this->assertEquals('email@email.com', $userDto->getEmail());
        $this->assertLessThanOrEqual((new \DateTimeImmutable()), $userDto->getCreated());
        $this->assertNull($userDto->getDeleted());
        $this->assertEquals('Some text here', $userDto->getNotes());

        $dto = new UserDto();
        $dto->setName('newname123')
            ->setEmail('newemail@email.com')
            ->setNotes('Some new text here');

        $updatedDto = $this->userService->update($userDto->getId(), $dto);

        $this->assertEquals(false, $updatedDto->isError());
        $this->assertEquals(1, $updatedDto->getId());
        $this->assertEquals('newname123', $updatedDto->getName());
        $this->assertEquals('newemail@email.com', $updatedDto->getEmail());
        $this->assertLessThanOrEqual($userDto->getCreated(), $updatedDto->getCreated());
        $this->assertNull($userDto->getDeleted());
        $this->assertEquals('Some new text here', $updatedDto->getNotes());
    }

    public function testUpdateNotExistsUser()
    {

        $dto = new UserDto();
        $dto->setName('newname123')
            ->setEmail('newemail@email.com')
            ->setNotes('Some new text here');

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Entity not found');

        $updatedDto = $this->userService->update(1, $dto);
    }

    public function testUpdateInvalidUser()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());
        $this->assertEquals('example123', $userDto->getName());
        $this->assertEquals('email@email.com', $userDto->getEmail());
        $this->assertLessThanOrEqual((new \DateTimeImmutable()), $userDto->getCreated());
        $this->assertNull($userDto->getDeleted());
        $this->assertEquals('Some text here', $userDto->getNotes());

        $dto = new UserDto();
        $dto->setName('new_1')
            ->setEmail('newemailemail.com')
            ->setNotes('Some new text here');

        $updatedDto = $this->userService->update(1, $dto);

        $this->assertEquals(true, $updatedDto->isError());

        $this->assertEquals(3, $updatedDto->getErrorsCount());

        $error = $updatedDto->getErrors()->get(0);
        $this->assertEquals('Your name must be at least 8 characters long', $error->getMessage());

        $error = $updatedDto->getErrors()->get(1);
        $this->assertEquals('Your name can contain only letters and numbers', $error->getMessage());

        $error = $updatedDto->getErrors()->get(2);
        $this->assertEquals('The email "newemailemail.com" is not a valid email.', $error->getMessage());
    }

    public function testDeleteUser()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());
        $this->assertNull($userDto->getDeleted());

        $deletedDto = $this->userService->delete($userDto->getId());

        $this->assertEquals(false, $deletedDto->isError());
        $this->assertEquals(1, $deletedDto->getId());
        $this->assertEquals('example123', $deletedDto->getName());
        $this->assertEquals('email@email.com', $deletedDto->getEmail());
        $this->assertLessThanOrEqual((new \DateTimeImmutable()), $deletedDto->getCreated());
        $this->assertNotNull($deletedDto->getDeleted());
        $this->assertEquals('Some text here', $deletedDto->getNotes());
    }

    public function testRestoreUser()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());

        $deletedDto = $this->userService->delete($userDto->getId());

        $this->assertEquals(false, $deletedDto->isError());
        $this->assertEquals(1, $deletedDto->getId());
        $this->assertNotNull($deletedDto->getDeleted());

        $restoredDto = $this->userService->restore($userDto->getId());

        $this->assertEquals(false, $restoredDto->isError());
        $this->assertEquals(1, $restoredDto->getId());
        $this->assertEquals('example123', $restoredDto->getName());
        $this->assertEquals('email@email.com', $restoredDto->getEmail());
        $this->assertEquals($userDto->getCreated(), $restoredDto->getCreated());
        $this->assertNull($restoredDto->getDeleted());
        $this->assertEquals('Some text here', $restoredDto->getNotes());
    }

    public function testValidUserList()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());

        $dto = new UserDto();
        $dto->setName('example1234')
            ->setEmail('email2@email.com')
            ->setNotes('Some new text here');

        $userDto2 = $this->userService->create($dto);

        $this->assertEquals(false, $userDto2->isError());
        $this->assertEquals(2, $userDto2->getId());

        $filter = [];

        $listDto = $this->userService->getList($filter, 'ASC', 1, 0);

        $this->assertEquals(0, $listDto->getOffset());
        $this->assertEquals(2, $listDto->getTotal());
        $this->assertEquals(1, $listDto->getLimit());

        /** @var UserDto $itemDto */
        $itemDto = $listDto->getData()->get(0);

        $this->assertEquals(false, $itemDto->isError());
        $this->assertEquals(1, $itemDto->getId());

        $this->assertEquals('example123', $itemDto->getName());
        $this->assertEquals('email@email.com', $itemDto->getEmail());
        $this->assertEquals($userDto->getCreated(), $itemDto->getCreated());
        $this->assertNull($itemDto->getDeleted());
        $this->assertEquals('Some text here', $itemDto->getNotes());
    }

    public function testValidSearchUserList()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());

        $dto = new UserDto();
        $dto->setName('example1234')
            ->setEmail('email2@email.com')
            ->setNotes('Some new text here');

        $userDto2 = $this->userService->create($dto);

        $this->assertEquals(false, $userDto2->isError());
        $this->assertEquals(2, $userDto2->getId());

        $filter = ['search' => 'email2'];

        $listDto = $this->userService->getList($filter, 'ASC', 1, 0);

        $this->assertEquals(0, $listDto->getOffset());
        $this->assertEquals(1, $listDto->getTotal());
        $this->assertEquals(1, $listDto->getLimit());

        /** @var UserDto $itemDto */
        $itemDto = $listDto->getData()->get(0);

        $this->assertEquals(false, $itemDto->isError());
        $this->assertEquals(2, $itemDto->getId());

        $this->assertEquals('example1234', $itemDto->getName());
        $this->assertEquals('email2@email.com', $itemDto->getEmail());
        $this->assertEquals($userDto2->getCreated(), $itemDto->getCreated());
        $this->assertNull($itemDto->getDeleted());
        $this->assertEquals('Some new text here', $itemDto->getNotes());
    }

    public function testActiveSearchUserList()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);
        $userDto = $this->userService->delete($userDto->getId());

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());
        $this->assertNotNull($userDto->getDeleted());

        $dto = new UserDto();
        $dto->setName('example1234')
            ->setEmail('email2@email.com')
            ->setNotes('Some new text here');

        $userDto2 = $this->userService->create($dto);
        $userDto2 = $this->userService->delete($userDto2->getId());

        $this->assertEquals(false, $userDto2->isError());
        $this->assertEquals(2, $userDto2->getId());
        $this->assertNotNull($userDto2->getDeleted());

        $filter = ['active' => true];

        $listDto = $this->userService->getList($filter, 'ASC', 10, 0);

        $this->assertEquals(0, $listDto->getOffset());
        $this->assertEquals(0, $listDto->getTotal());
        $this->assertEquals(10, $listDto->getLimit());
        $this->assertEmpty($listDto->getData());
    }

    public function testEmptyActiveSearchUserList()
    {
        $dto = new UserDto();
        $dto->setName('example123')
            ->setEmail('email@email.com')
            ->setNotes('Some text here');

        $userDto = $this->userService->create($dto);
        $userDto = $this->userService->delete($userDto->getId());

        $this->assertEquals(false, $userDto->isError());
        $this->assertEquals(1, $userDto->getId());
        $this->assertNotNull($userDto->getDeleted());

        $dto = new UserDto();
        $dto->setName('example1234')
            ->setEmail('email2@email.com')
            ->setNotes('Some new text here');

        $userDto2 = $this->userService->create($dto);

        $this->assertEquals(false, $userDto2->isError());
        $this->assertEquals(2, $userDto2->getId());
        $this->assertNull($userDto2->getDeleted());

        $filter = ['active' => true];

        $listDto = $this->userService->getList($filter, 'ASC', 10, 0);

        $this->assertEquals(0, $listDto->getOffset());
        $this->assertEquals(1, $listDto->getTotal());
        $this->assertEquals(10, $listDto->getLimit());

        /** @var UserDto $itemDto */
        $itemDto = $listDto->getData()->get(0);

        $this->assertEquals(false, $itemDto->isError());
        $this->assertEquals(2, $itemDto->getId());

        $this->assertEquals('example1234', $itemDto->getName());
        $this->assertEquals('email2@email.com', $itemDto->getEmail());
        $this->assertEquals($userDto2->getCreated(), $itemDto->getCreated());
        $this->assertNull($itemDto->getDeleted());
        $this->assertEquals('Some new text here', $itemDto->getNotes());

    }
}