<?php

namespace App\Models;

class LoginHistoryModel extends ModelBase
{
    protected string $table = 'login_history';
    
    public function createLoginHistory(int $userId, string $ip, string $userAgent): int
    {
        return $this->create([
            'user_id' => $userId,
            'ip' => $ip,
            'user_agent' => $userAgent,
        ]);
    }
    
    public function getLoginHistoryByUserId(int $userId, int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC LIMIT ?"
        );
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }
}
