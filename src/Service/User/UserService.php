<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Dto\Assembler\UserAssembler;
use App\Dto\PaginatedCollectionDto;
use App\Dto\User\UserDto;
use App\Entity\User;
use App\Exception\EntityNotFoundException;
use App\Repository\UserRepository;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService implements UserServiceInterface
{
    /** @var EntityManagerInterface */
    protected EntityManagerInterface $entityManager;

    /** @var ValidatorInterface */
    protected ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @throws ConnectionException
     * @throws \Throwable
     */
    public function create(UserDto $dto): UserDto
    {
        /**
         * Вместо Assembler можно использовать Serializer/Normalizer для гидрации
         * Здесь тупой маппинг по двум причинам
         * 1. Гидрация данные в бизнес сущность в автоматическом режиме как-то не надежно (могут быть сайды)
         * 2. Надо запариваться с настройкой (мало времени)
         * 3. Простая сериализация - это рефлексия. Рефлексия это долго (хотя кэш метаданных классов помогает)
         *
         * Лучше всего получается использовать автоматическую гидрацию в связке Request -> RequestDto -> Service -> ResponseDto
         * то есть каждый реквест json -> RequestDto, а каждый респонс - ResponseDto -> json
         *
         * Сейчас dto и assembler рантайм классы, то есть их нет в контейнере
         * В целом, можно сделать для них Registry и тянуть их экземпляры оттуда, тогда можно будет подменять на уровне Registry
         *
         */
        $userAssembler = new UserAssembler();

        $user = $userAssembler->mapEntityFromDto(new User(), $dto);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $dto->setErrors($errors);
            return $dto;
        }

        $this->entityManager->persist($user);
        $this->flush();

        return $userAssembler->getDtoFromEntity($user);
    }

    /**
     * @throws ConnectionException|EntityNotFoundException
     * @throws \Throwable
     */
    public function update(int $id, UserDto $dto): UserDto
    {
        $userAssembler = new UserAssembler();

        /** @var User $user */
        $user = $this->entityManager->find(User::class, $id);

        if (is_null($user)) {
            throw new EntityNotFoundException('Entity not found');
        }

        $user = $userAssembler->mapEntityFromDto($user, $dto);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $dto->setErrors($errors);
            return $dto;
        }

        $this->entityManager->persist($user);
        $this->flush();

        return $userAssembler->getDtoFromEntity($user);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get(int $id): UserDto
    {
        $userAssembler = new UserAssembler();

        /** @var User $user */
        $user = $this->entityManager->find(User::class, $id);

        if (is_null($user)) {
            throw new EntityNotFoundException('Entity not found');
        }

        return $userAssembler->getDtoFromEntity($user);
    }

    /**
     * @throws ConnectionException|EntityNotFoundException
     * @throws \Throwable
     */
    public function delete(int $id): UserDto
    {
        $userAssembler = new UserAssembler();

        /** @var User $user */
        $user = $this->entityManager->find(User::class, $id);

        if (is_null($user)) {
            throw new EntityNotFoundException('Entity not found');
        }

        $user->setDeleted(new \DateTime());

        $this->entityManager->persist($user);
        $this->flush();

        return $userAssembler->getDtoFromEntity($user);
    }

    /**
     * @throws ConnectionException|EntityNotFoundException
     * @throws \Throwable
     */
    public function restore(int $id): UserDto
    {
        $userAssembler = new UserAssembler();

        /** @var User $user */
        $user = $this->entityManager->find(User::class, $id);

        if (is_null($user)) {
            throw new EntityNotFoundException('Entity not found');
        }

        $user->setDeleted(null);

        $this->entityManager->persist($user);
        $this->flush();

        return $userAssembler->getDtoFromEntity($user);
    }

    /**
     * Фильтр можно заменить на ValueObject или тот же Dto, чтобы получить документированную фильтрацию
     *
     * @param array $filter
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return PaginatedCollectionDto
     * @throws \Throwable
     */
    public function getList(array $filter, string $order, int $limit, int $offset): PaginatedCollectionDto
    {
        /** @var UserRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);

        $total = $repository->countList($filter);
        $collection = $repository->getList($filter, $order, $limit, $offset);

        $data = $collection->map(function ($entity) {
            return (new UserAssembler())->getDtoFromEntity($entity);
        });

        return new PaginatedCollectionDto($data, $offset, $limit, $total);
    }

    /**
     * Можно вынести в родительский класс сервисов, которые работают с EntityManager (если в этом есть смысл)
     *
     * @throws ConnectionException
     * @throws \Throwable
     */
    private function flush(): void
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Throwable $exception) {
            $this->entityManager->getConnection()->rollBack();
            throw $exception;
        }
    }
}