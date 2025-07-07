<?php

namespace App\Models;

class UserModel extends ModelBase
{
    protected string $table = 'users';
    
    public function findById(int $id): ?array
    {
        return $this->find($id);
    }
    
    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }
    
    public function findByUsername(string $username): ?array
    {
        return $this->findBy('username', $username);
    }
    
    public function createUser(string $username, string $email, string $password): int
    {
        return $this->create([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }
    
    public function verifyPassword(array $user, string $password): bool
    {
        return password_verify($password, $user['password']);
    }
    
    public function updateUser(int $id, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->update($id, $data);
    }
    
    public function deleteUser(int $id): bool
    {
        return $this->delete($id);
    }
}
