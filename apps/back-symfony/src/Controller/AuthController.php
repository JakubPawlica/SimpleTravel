<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\User;
use App\Service\AuthService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use OpenApi\Annotations as OA;

/**
* @OA\Tag(name="🔒 Auth")
*/
class AuthController extends AbstractController
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Rejestracja nowego użytkownika",
     *     tags={"🔒 Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="Jan Kowalski"),
     *             @OA\Property(property="email", type="string", format="email", example="jan@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="tajnehaslo123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Użytkownik zarejestrowany"),
     *     @OA\Response(response=400, description="Brak danych lub zły format"),
     *     @OA\Response(response=409, description="Email już w użyciu")
     * )
     */
    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['email'], $data['password'])) {
            throw new BadRequestHttpException('Invalid registration data');
        }

        $this->authService->register($data);
        return $this->json(['message' => 'User registered'], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Logowanie użytkownika",
     *     tags={"🔒 Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="jan@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="tajnehaslo123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Zalogowano"),
     *     @OA\Response(response=400, description="Nieprawidłowe dane wejściowe"),
     *     @OA\Response(response=401, description="Błędne dane logowania")
     * )
     */
    #[Route('/api/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, SessionInterface $session, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['email'], $data['password'])) {
            throw new BadRequestHttpException('Invalid credentials');
        }

        $user = $this->authService->validateCredentials($data['email'], $data['password']);
        if (!$user) {
            throw new UnauthorizedHttpException('', 'Niepoprawne dane logowania');
        }

        $session->set('user_id', $user->getId());

        return $this->json(['message' => 'Logged in']);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Wylogowanie użytkownika",
     *     tags={"🔒 Auth"},
     *     @OA\Response(response=200, description="Wylogowano")
     * )
     */
    #[Route('/api/logout', name: 'logout', methods: ['POST'])]
    public function logout(SessionInterface $session): JsonResponse
    {
        $session->clear();
        return $this->json(['message' => 'Logged out']);
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Pobierz dane zalogowanego użytkownika",
     *     tags={"🔒 Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Dane użytkownika",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Jan Kowalski"),
     *             @OA\Property(property="email", type="string", example="jan@example.com")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Nie zalogowano")
     * )
     */
    #[Route('/api/me', name: 'me', methods: ['GET'])]
    public function me(SessionInterface $session): JsonResponse
    {
        $userId = $session->get('user_id');

        if (!$userId) {
            throw new UnauthorizedHttpException('', 'Not logged in');
        }

        $user = $this->authService->getUserById($userId);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        return $this->json($user, 200, [], ['groups' => 'user:read']);
    }
}
