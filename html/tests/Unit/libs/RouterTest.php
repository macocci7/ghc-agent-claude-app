<?php

namespace Tests\Unit\Libs;

use PHPUnit\Framework\TestCase;
use Libs\Router;

class RouterTest extends TestCase
{
    private $router;

    protected function setUp(): void
    {
        $this->router = new \Router();
    }

    public function test_get_route_registration()
    {
        $called = false;
        $this->router->get('/test', function() use (&$called) {
            $called = true;
        });

        ob_start();
        $this->router->dispatch('GET', '/test');
        ob_end_clean();

        $this->assertTrue($called);
    }

    public function test_post_route_registration()
    {
        $called = false;
        $this->router->post('/test', function() use (&$called) {
            $called = true;
        });

        ob_start();
        $this->router->dispatch('POST', '/test');
        ob_end_clean();

        $this->assertTrue($called);
    }

    public function test_route_not_found_returns_404()
    {
        ob_start();
        $this->router->dispatch('GET', '/nonexistent');
        $content = ob_get_clean();

        $this->assertEquals(404, http_response_code());
    }

    public function test_method_not_matched()
    {
        $called = false;
        $this->router->get('/test', function() use (&$called) {
            $called = true;
        });

        ob_start();
        $this->router->dispatch('POST', '/test');
        ob_end_clean();

        $this->assertFalse($called);
        $this->assertEquals(404, http_response_code());
    }

    public function test_query_string_is_ignored()
    {
        $called = false;
        $this->router->get('/test', function() use (&$called) {
            $called = true;
        });

        ob_start();
        $this->router->dispatch('GET', '/test?param=value');
        ob_end_clean();

        $this->assertTrue($called);
    }
}
