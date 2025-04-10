<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\AuthService;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthServiceTest extends TestCase
{
    private AuthService $authService;
    private $em;
    private $userRepository;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        $this->authService = new AuthService($this->em, $this->userRepository);
    }

    public function testRegisterSuccess(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret'
        ];

        $this->userRepository
            ->method('findOneBy')
            ->with(['email' => $data['email']])
            ->willReturn(null);

        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');

        $user = $this->authService->register($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Test User', $user->getName());
        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertNotEmpty($user->getSessionToken());
    }

    public function testRegisterThrowsExceptionForMissingData(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->authService->register(['name' => 'Only name']);
    }

    public function testRegisterThrowsExceptionForExistingEmail(): void
    {
        $data = [
            'name' => 'Existing',
            'email' => 'taken@example.com',
            'password' => '1234'
        ];

        $this->userRepository
            ->method('findOneBy')
            ->with(['email' => $data['email']])
            ->willReturn(new User());

        $this->expectException(\RuntimeException::class);
        $this->authService->register($data);
    }

    public function testValidateCredentialsSuccess(): void
    {
        $user = new User();
        $user->setEmail('valid@example.com');
        $user->setPasswordHash(password_hash('correct-password', PASSWORD_DEFAULT));

        $this->userRepository
            ->method('findOneBy')
            ->with(['email' => 'valid@example.com'])
            ->willReturn($user);

        $result = $this->authService->validateCredentials('valid@example.com', 'correct-password');
        $this->assertSame($user, $result);
    }

    public function testValidateCredentialsFail(): void
    {
        $user = new User();
        $user->setEmail('wrong@example.com');
        $user->setPasswordHash(password_hash('correct-password', PASSWORD_DEFAULT));

        $this->userRepository
            ->method('findOneBy')
            ->with(['email' => 'wrong@example.com'])
            ->willReturn($user);

        $result = $this->authService->validateCredentials('wrong@example.com', 'wrong-password');
        $this->assertNull($result);
    }

    public function testGetUserByIdSuccess(): void
    {
        $user = new User();
        $this->userRepository
            ->method('find')
            ->with(1)
            ->willReturn($user);

        $result = $this->authService->getUserById(1);
        $this->assertSame($user, $result);
    }
}
