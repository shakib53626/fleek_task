<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserDatabaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_be_created_using_factory()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    #[Test]
    public function multiple_users_can_be_created()
    {
        User::factory()->count(5)->create();
        $this->assertDatabaseCount('users', 5);
    }
}
