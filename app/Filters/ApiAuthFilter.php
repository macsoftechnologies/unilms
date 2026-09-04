<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\OrganizationModel;
use App\Models\OrgUserModel;
use App\Models\ParentModel;

class ApiAuthFilter implements FilterInterface
{
    public static function getSecret(): string
    {
        $key = env('encryption.key') ?: 'UniLmsSecretKey2026';
        if (str_starts_with($key, 'hex2bin:')) {
            $key = hex2bin(substr($key, 8));
        }
        return $key;
    }

    public static function createToken(array $payload, int $ttlSeconds = 2592000): string // 30 days
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + $ttlSeconds;

        $secret = self::getSecret();
        $headerEncoded = rtrim(strtr(base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256'])), '+/', '-_'), '=');
        $payloadEncoded = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
        $signature = hash_hmac('sha256', "{$headerEncoded}.{$payloadEncoded}", $secret, true);
        $signatureEncoded = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "{$headerEncoded}.{$payloadEncoded}.{$signatureEncoded}";
    }

    public static function verifyToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;

        [$headerB64, $payloadB64, $signatureB64] = $parts;
        $secret = self::getSecret();

        $expectedSig = hash_hmac('sha256', "{$headerB64}.{$payloadB64}", $secret, true);
        $expectedSigB64 = rtrim(strtr(base64_encode($expectedSig), '+/', '-_'), '=');

        if (!hash_equals($expectedSigB64, $signatureB64)) {
            return null; // Tampered or invalid signature
        }

        $payload = json_decode(base64_decode(strtr($payloadB64, '-_', '+/')), true);
        if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) {
            return null; // Expired
        }

        return $payload;
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        $token = null;

        if (!empty($authHeader) && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = trim($matches[1]);
        } else {
            // Also check query param or custom header
            $token = $request->getGet('token') ?: $request->getHeaderLine('X-API-TOKEN');
        }

        if (!$token) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Authorization header (Bearer token) is missing.'
                ]);
        }

        $payload = self::verifyToken($token);
        if (!$payload) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid, tampered, or expired access token. Please re-authenticate.'
                ]);
        }

        // Verify organization status
        $org = (new OrganizationModel())->find($payload['org_id'] ?? 0);
        if (!$org || $org['status'] !== 'active') {
            return service('response')
                ->setStatusCode(403)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'The educational institution account is currently inactive or suspended.'
                ]);
        }

        // Verify role / user type if required in filter argument
        if (!empty($arguments)) {
            $requiredType = $arguments[0]; // e.g. 'student' or 'parent'
            if (($payload['user_type'] ?? '') !== $requiredType) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON([
                        'status' => 'error',
                        'message' => "Access denied. This endpoint is restricted to {$requiredType} accounts."
                    ]);
            }
        }

        // Store user payload on request for controller access
        $request->api_user = $payload;

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Add standard CORS and JSON headers
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization, X-API-TOKEN');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}
