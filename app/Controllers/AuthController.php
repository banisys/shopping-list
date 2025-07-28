<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Token;
use App\Models\User;

class AuthController
{
    private const TOKEN_COOKIE_CONFIG = [
        'expires' => 60 * 60 * 24 * 7,
        'path' => '/',
        'httponly' => false,
        'secure' => false,
        'samesite' => 'Lax'
    ];

    public function login(): void
    {
        $data = $this->validateLoginInput(Request::body());
        $user = $this->authenticateUser($data['mobile'], $data['password']);
        $token = $this->generateAndStoreToken($user->id);

        $this->setTokenCookie($token);

        Response::json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $this->formatUserResponse($user)
        ]);
    }


    public function register(): void
    {
        $data = $this->validateRegisterInput(Request::body());

        if (User::findByMobile($data['mobile'])) {
            Response::json(['error' => 'User already exists'], 409);
        }

        $user = $this->createUser($data);
        $token = $this->generateAndStoreToken($user->id);

        $this->setTokenCookie($token);

        Response::json([
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $this->formatUserResponse($user)
        ], 201);
    }

    private function validateLoginInput(array $data): array
    {
        if (empty($data['mobile']) || empty($data['password'])) {
            Response::json(['error' => 'mobile and password are required'], 422);
        }

        return [
            'mobile' => trim($data['mobile']),
            'password' => $data['password']
        ];
    }


    private function validateRegisterInput(array $data): array
    {
        if (empty($data['name']) || empty($data['mobile']) || empty($data['password'])) {
            Response::json(['error' => 'Name, mobile, and password are required'], 422);
        }

        return [
            'name' => trim($data['name']),
            'mobile' => trim($data['mobile']),
            'password' => $data['password'],
            'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s')
        ];
    }

    private function authenticateUser(string $mobile, string $password): User
    {
        $user = User::findByMobile($mobile);

        if (!$user || !password_verify($password, $user->password)) {
            Response::json(['error' => 'Invalid credentials'], 401);
        }

        return $user;
    }


    private function createUser(array $data): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->mobile = $data['mobile'];
        $user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->created_at = $data['created_at'];

        if (!$user->save()) {
            Response::json(['error' => 'Failed to create user'], 500);
        }

        return $user;
    }


    private function generateAndStoreToken(int $userId): string
    {
        return Token::create($userId);
    }


    private function setTokenCookie(string $token): void
    {
        setcookie('token', $token, [
            'expires' => time() + self::TOKEN_COOKIE_CONFIG['expires'],
            'path' => self::TOKEN_COOKIE_CONFIG['path'],
            'httponly' => self::TOKEN_COOKIE_CONFIG['httponly'],
            'secure' => self::TOKEN_COOKIE_CONFIG['secure'],
            'samesite' => self::TOKEN_COOKIE_CONFIG['samesite']
        ]);
    }


    private function formatUserResponse(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name ?? null,
            'mobile' => $user->mobile
        ];
    }
}
