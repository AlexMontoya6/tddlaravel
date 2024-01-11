<?php

namespace Tests\Unit;

use App\{User, Login};
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function gets_the_last_loguin_datetime_of_each_user()
    {
        $joel = factory(User::class)->create(['first_name' => 'Joel']);

        factory(Login::class)->create([
            'user_id' => $joel->id,
            'created_at' => '2020-09-18 12:30:00',
        ]);
        factory(Login::class)->create([
            'user_id' => $joel->id,
            'created_at' => '2020-09-18 12:31:00',
        ]);
        factory(Login::class)->create([
            'user_id' => $joel->id,
            'created_at' => '2020-09-17 12:31:00',
        ]);

        $elli = factory(User::class)->create(['first_name' => 'Elli']);

        factory(Login::class)->create([
            'user_id' => $elli->id,
            'created_at' => '2020-09-15 12:00:00',
        ]);
        factory(Login::class)->create([
            'user_id' => $elli->id,
            'created_at' => '2020-09-15 12:01:00',
        ]);
        factory(Login::class)->create([
            'user_id' => $elli->id,
            'created_at' => '2020-09-15 11:59:59',
        ]);

        $users = User::withLastLogin()->get();

        $this->assertEquals(
            Carbon::parse('2020-09-18 12:31:00'),
            $users->firstWhere('first_name', 'Joel')->last_login_at
        );

        $this->assertEquals(
            Carbon::parse('2020-09-15 12:01:00'),
            $users->firstWhere('first_name', 'Elli')->last_login_at
        );
    }
}