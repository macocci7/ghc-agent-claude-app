<?php

namespace Tests\Http;

class BasicHttpTest extends HttpTestCase
{
    public function test_homepage_returns_ok_status()
    {
        $this->get('/');
        $this->assertOk();
    }

    public function test_homepage_contains_expected_content()
    {
        $this->get('/');
        $this->assertOk();
        $this->assertSee('Login');
    }

    public function test_login_page_returns_ok_status()
    {
        $this->get('/login');
        $this->assertOk();
    }

    public function test_login_page_contains_form()
    {
        $this->get('/login');
        $this->assertOk();
        $this->assertSee('<form');
        $this->assertSee('email');
        $this->assertSee('password');
    }

    public function test_signup_page_returns_ok_status()
    {
        $this->get('/signup');
        $this->assertOk();
    }

    public function test_signup_page_contains_form()
    {
        $this->get('/signup');
        $this->assertOk();
        $this->assertSee('<form');
        $this->assertSee('email');
        $this->assertSee('password');
        $this->assertSee('password_confirmation');
    }

    public function test_dashboard_redirects_when_not_authenticated()
    {
        $this->get('/dashboard');
        $this->assertRedirect('/login');
    }

    public function test_nonexistent_page_returns_404()
    {
        $this->get('/nonexistent-page');
        $this->assertStatus(404);
    }
}
