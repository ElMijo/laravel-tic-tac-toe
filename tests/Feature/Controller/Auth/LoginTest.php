<?php

namespace Tests\Feature\Controller\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to validate the login view exist.
     *
     * @test
     */
    public function loginView()
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertViewIs('auth.login')
        ;
    }

    /**
     * Test to validate the redirect to login when the user is not authenticated.
     *
     * @test
     */
    public function loginViewRedirect()
    {
        $this->get('/')
            ->assertStatus(302)
            ->assertRedirect('/login')
        ;
    }

    /**
     * Test to validate the login process with empty parameters.
     *
     * @test
     */
    public function loginViewEmptyParams()
    {
        $this->post('/login')
            ->assertStatus(302)
            ->assertSessionHasErrors()
        ;
    }

    /**
     * Test to validate login process with invalid parameters.
     *
     * @test
     */
    public function loginViewInvalidParams()
    {
        $this->post('/login', ['email' => 'any@domai'])
            ->assertStatus(302)
            ->assertSessionHasErrors()
        ;
    }

    /**
     * Test to validate login process with valid parameters.
     *
     * @test
     */
    public function loginViewValidParams()
    {
        $user = factory(\App\User::class)->create();
        $this->post('/login', ['email' => $user->email, 'password' => 'secret'])
            ->assertRedirect('/')
        ;
        $this->assertAuthenticatedAs($user);
    }
}
