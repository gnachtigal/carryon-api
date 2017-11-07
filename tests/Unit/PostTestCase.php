<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Post;
use App\User;

class PostTestCase extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste@teste.com',
            'password' => bcrypt(123456),
            'voluntary' => false,
        ]);

        $voluntary = User::create([
            'name' => 'Voluntário Teste',
            'email' => 'voluntario@teste.com',
            'password' => bcrypt(123456),
            'voluntary' => true,
        ]);

        $post = Post::create([
            'title' => 'Postagem Teste',
            'user_id' => $user->id,
        ]);

        $this->assertFalse(true);

    }
}
