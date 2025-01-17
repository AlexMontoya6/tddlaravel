<?php

namespace Tests\Feature\Admin;

use App\{User, Skill};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_completely_deletes_a_user()
    {
        $user = factory(User::class)->create([
            'deleted_at' => now(),
        ]);

        $this->delete('usuarios/' . $user->id)
            ->assertRedirect('usuarios/papelera');

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function it_cannot_delete_a_user_that_is_not_in_the_trash()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create([
            'deleted_at' => null,
        ]);

        $this->delete('usuarios/' . $user->id)
            ->assertStatus(404);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    function it_sends_a_user_to_the_trash()
    {
        $user = factory(User::class)->create();


        $user->skills()->attach(factory(Skill::class)->create());

        $this->patch('usuarios/' . $user->id . '/papelera')
            ->assertRedirect('usuarios');

        //Opción 1
        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);

        $this->assertSoftDeleted('skill_user', [
            'user_id' => $user->id,
        ]);

        $this->assertSoftDeleted('user_profiles', [
            'user_id' => $user->id,
        ]);

        //Opción 2
        $user->refresh();
        $this->assertTrue($user->trashed());
    }

    /** @test */
    function it_shows_the_deleted_users()
    {
        $user = factory(User::class)->create([
            'first_name' => 'Joel',
            'deleted_at' => now()
        ]);

        factory(User::class)->create([
            'first_name' => 'Ellie',
        ]);

        $this->get('usuarios/papelera')
            ->assertStatus(200)
            //->assertSee(trans('users.title.trash'))
            ->assertSee('Joel')
            ->assertDontSee('Ellie');

    }

}
