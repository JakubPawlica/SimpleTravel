<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class AuthService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository
    ) {}

    public function register(array $data): User
    {
        if (!isset($data['name'], $data['email'], $data['password'])) {
            throw new \InvalidArgumentException('Invalid data');
        }

        if ($this->userRepository->findOneBy(['email' => $data['email']])) {
            throw new ConflictHttpException('Email jest już zajęty');
        }

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPasswordHash(password_hash($data['password'], PASSWORD_DEFAULT));
        $user->setSessionToken(bin2hex(random_bytes(32)));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function validateCredentials(string $email, string $password): ?User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user || !password_verify($password, $user->getPasswordHash())) {
            return null;
        }

        return $user;
    }

    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }
}
