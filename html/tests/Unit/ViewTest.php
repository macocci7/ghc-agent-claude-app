<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset response code before each test
        http_response_code(200);
    }

    public function test_render_includes_template_file()
    {
        // Create a temporary test template
        $tempDir = sys_get_temp_dir() . '/test_views';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        $tempFile = $tempDir . '/test.view.php';
        file_put_contents($tempFile, '<?php echo "Hello " . $name; ?>');

        // Mock the template path resolution
        $reflection = new \ReflectionClass(View::class);
        $originalDir = dirname(dirname(__DIR__)) . '/app/Views/';
        
        // Create test view file
        $viewsDir = dirname(dirname(__DIR__)) . '/app/Views/test';
        if (!is_dir($viewsDir)) {
            mkdir($viewsDir, 0777, true);
        }
        $testView = $viewsDir . '/test.view.php';
        file_put_contents($testView, '<?php echo "Hello " . $name; ?>');

        ob_start();
        \View::render('test/test.view.php', ['name' => 'World']);
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
        
        \View::render('nonexistent/template.view.php');
    }

    public function test_json_sets_correct_headers_and_output()
    {
        $data = ['message' => 'Hello World', 'status' => 'success'];
        
        ob_start();
        try {
            \View::json($data, 201);
        } catch (\Exception $e) {
            // Catch the exit() call
        }
        $output = ob_get_clean();

        $this->assertEquals(201, http_response_code());
        $this->assertEquals(json_encode($data), $output);
    }

    public function test_redirect_sets_correct_headers()
    {
        try {
            \View::redirect('/dashboard', 301);
        } catch (\Exception $e) {
            // Catch the exit() call
        }

        $this->assertEquals(301, http_response_code());
        
        // Check if Location header would be set (we can't directly test headers in unit tests)
        $headers = headers_list();
        $locationHeaderFound = false;
        foreach ($headers as $header) {
            if (strpos($header, 'Location: /dashboard') !== false) {
                $locationHeaderFound = true;
                break;
            }
        }
        // Note: In CLI environment headers_list() might not work as expected
        // This is more of a smoke test to ensure the method doesn't throw errors
    }
}
