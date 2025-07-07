<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\ModelBase;
use PDO;
use PDOStatement;

class ModelBaseTest extends TestCase
{
    private $model;
    private $mockPdo;
    private $mockStatement;

    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);
        
        // Create an anonymous class that extends ModelBase for testing
        $this->model = new class($this->mockPdo) extends ModelBase {
            protected string $table = 'test_table';
            
            public function __construct($mockPdo)
            {
                // Override constructor to inject mock PDO
                $this->db = $mockPdo;
            }
            
            public function testFind(int $id): ?array
            {
                return $this->find($id);
            }
            
            public function testFindBy(string $column, $value): ?array
            {
                return $this->findBy($column, $value);
            }
            
            public function testCreate(array $data): int
            {
                return $this->create($data);
            }
            
            public function testUpdate(int $id, array $data): bool
            {
                return $this->update($id, $data);
            }
            
            public function testDelete(int $id): bool
            {
                return $this->delete($id);
            }
        };
    }

    public function test_find_returns_record_when_found()
    {
        $expectedResult = ['id' => 1, 'name' => 'Test'];
        
        $this->mockPdo->expects($this->once())
                      ->method('prepare')
                      ->with('SELECT * FROM test_table WHERE id = ?')
                      ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
                           ->method('execute')
                           ->with([1]);
        
        $this->mockStatement->expects($this->once())
                           ->method('fetch')
                           ->willReturn($expectedResult);
        
        $result = $this->model->testFind(1);
        $this->assertEquals($expectedResult, $result);
    }

    public function test_find_returns_null_when_not_found()
    {
        $this->mockPdo->expects($this->once())
                      ->method('prepare')
                      ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
                           ->method('execute');
        
        $this->mockStatement->expects($this->once())
                           ->method('fetch')
                           ->willReturn(false);
        
        $result = $this->model->testFind(999);
        $this->assertNull($result);
    }

    public function test_find_by_returns_record_when_found()
    {
        $expectedResult = ['id' => 1, 'email' => 'test@example.com'];
        
        $this->mockPdo->expects($this->once())
                      ->method('prepare')
                      ->with('SELECT * FROM test_table WHERE email = ?')
                      ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
                           ->method('execute')
                           ->with(['test@example.com']);
        
        $this->mockStatement->expects($this->once())
                           ->method('fetch')
                           ->willReturn($expectedResult);
        
        $result = $this->model->testFindBy('email', 'test@example.com');
        $this->assertEquals($expectedResult, $result);
    }

    public function test_create_inserts_record_and_returns_id()
    {
        $data = ['name' => 'Test', 'email' => 'test@example.com'];
        
        $this->mockPdo->expects($this->once())
                      ->method('prepare')
                      ->with('INSERT INTO test_table (name, email) VALUES (:name, :email)')
                      ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
                           ->method('execute')
                           ->with($data);
        
        $this->mockPdo->expects($this->once())
                      ->method('lastInsertId')
                      ->willReturn('123');
        
        $result = $this->model->testCreate($data);
        $this->assertEquals(123, $result);
    }

    public function test_update_modifies_record()
    {
        $data = ['name' => 'Updated Test'];
        
        $this->mockPdo->expects($this->once())
                      ->method('prepare')
                      ->with('UPDATE test_table SET name = :name WHERE id = :id')
                      ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
                           ->method('execute')
                           ->with(['name' => 'Updated Test', 'id' => 1])
                           ->willReturn(true);
        
        $result = $this->model->testUpdate(1, $data);
        $this->assertTrue($result);
    }

    public function test_delete_removes_record()
    {
        $this->mockPdo->expects($this->once())
                      ->method('prepare')
                      ->with('DELETE FROM test_table WHERE id = ?')
                      ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
                           ->method('execute')
                           ->with([1])
                           ->willReturn(true);
        
        $result = $this->model->testDelete(1);
        $this->assertTrue($result);
    }
}