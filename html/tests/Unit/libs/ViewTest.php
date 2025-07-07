<?php

namespace Tests\Unit\Libs;

use PHPUnit\Framework\TestCase;
use Libs\View;

class ViewTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset response code before each test
        http_response_code(200);
    }

    public function test_render_includes_template_file()
    {
        // Create test view file
        $viewsDir = dirname(dirname(dirname(__DIR__))) . '/app/Views/test';
        if (!is_dir($viewsDir)) {
            mkdir($viewsDir, 0777, true);
        }
        $testView = $viewsDir . '/test.view.php';
        file_put_contents($testView, '<?php echo "Hello " . $name; ?>');

        ob_start();
        View::render('test/test.view.php', ['name' => 'World']);
        $output = ob_get_clean();

        $this->assertEquals('Hello World', $output);

        // Clean up
        unlink($testView);
        rmdir($viewsDir);
    }

    public function test_render_throws_exception_for_missing_template()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Template not found:');
        
        View::render('nonexistent/template.view.php');
    }
}
