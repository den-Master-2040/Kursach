<?php

// Создание компонента для работы с JWT
namespace app\components;

use Firebase\JWT\JWT;
use \Firebase\JWT\Key;
// use yii\base\Component;

class JwtUtil
{
    // Ключ для подписи токена
    private $key = 'ddfgdfg_df323@@!njdfg@5dfgdf@vvb04Dgb5sb'; // Замените на свой секретный ключ

    // Генерация JWT токена
    public function generateToken($userId)
    {
        $tokenId = base64_encode(random_bytes(32));
        $issuedAt = time();
        $expire = $issuedAt + 3600; // Токен будет действителен в течение 1 часа

        $data = [
            'iat' => $issuedAt,
            'jti' => $tokenId,
            'exp' => $expire,
            'data' => [
                'userId' => $userId,
            ]
        ];

        return JWT::encode($data, $this->key, 'HS512');
    }

    // Проверка и декодирование JWT токена
    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS512'));
            return $decoded->data->userId;
        } catch (\Exception $e) {
            return null;
        }
    }
}
