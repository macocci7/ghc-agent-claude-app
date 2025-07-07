<?php

namespace Tests\Unit\Libs;

use PHPUnit\Framework\TestCase;
use Libs\Database;

class DatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset connection before each test
        Database::resetConnection();
    }

    protected function tearDown(): void
    {
        // Reset connection after each test
        Database::resetConnection();
    }

    public function test_get_connection_returns_pdo_instance()
    {
        $connection = Database::getConnection();
        $this->assertInstanceOf(\PDO::class, $connection);
    }

    public function test_get_connection_returns_same_instance()
    {
        $connection1 = Database::getConnection();
        $connection2 = Database::getConnection();
        $this->assertSame($connection1, $connection2);
    }

    public function test_reset_connection_clears_singleton()
    {
        $connection1 = Database::getConnection();
        Database::resetConnection();
        $connection2 = Database::getConnection();
        $this->assertNotSame($connection1, $connection2);
    }

    public function test_connection_has_correct_attributes()
    {
        $connection = Database::getConnection();
        $this->assertEquals(\PDO::ERRMODE_EXCEPTION, $connection->getAttribute(\PDO::ATTR_ERRMODE));
        $this->assertEquals(\PDO::FETCH_ASSOC, $connection->getAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE));
    }
}
