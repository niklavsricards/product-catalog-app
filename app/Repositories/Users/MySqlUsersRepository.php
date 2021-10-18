<?php

namespace App\Repositories\Users;

use App\Models\User;
use PDO;

class MySqlUsersRepository implements UsersRepository
{
    public PDO $pdo;

    public function __construct($config)
    {
        $this->pdo = new PDO(
            "mysql:host={$config['host']};
            port={$config['port']};
            dbname={$config['dbName']}",
            "{$config['username']}",
            "{$config['password']}"
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function register(User $user): void
    {
        $statement = $this->pdo->prepare('INSERT INTO users (name, email, id, password)
        VALUE (:name, :email, :id, :password)');

        $statement->bindValue(':name', $user->name());
        $statement->bindValue(':email', $user->email());
        $statement->bindValue(':id', $user->userId());
        $statement->bindValue(':password', $user->password());

        $statement->execute();
    }

    public function login(string $email): ?User
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->bindValue(':email', $email);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            return new User(
                $result['name'],
                $result['email'],
                $result['id'],
                $result['password']
            );
        } else {
            return null;
        }
    }

    public function isEmailTaken(string $email): bool
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->bindValue(':email', $email);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) return false;
        else return true;
    }
}