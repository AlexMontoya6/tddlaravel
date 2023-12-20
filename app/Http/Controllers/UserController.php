<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Profession;
use App\Skill;
use App\Sortable;
use App\User;
use App\UserFilter;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    public function index(UserFilter $userFilter, Sortable $sortable)
    {
        $users = User::query()
            ->with('team', 'skills', 'profile.profession')
            ->when(request('team'), function (Builder $query, $team) {
                if ($team === 'with_team') {
                    $query->has('team');
                } elseif ($team === 'without_team') {
                    $query->doesntHave('team');
                }
            })
            ->filterBy($userFilter, request()->only(['state', 'role', 'search', 'skills', 'from', 'to']))
            ->orderBy('created_at', 'DESC')
            ->paginate();

        $users->appends($userFilter->valid());

        $sortable->setCurrentOrder(request('order'), request('direction'));

        return view('users.index')
            ->with([
                'users' => $users,
                'view' => 'index',
                'skills' => Skill::orderBy('name')->get(),
                'checkedSkills' => collect(request('skills')),
                'sortable' => $sortable,
            ]);
    }

    public function trashed()
    {
        return view('users.index')
            ->with([
                'users' => User::onlyTrashed()->paginate(),
                'view' => 'trash',
            ]);
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function create()
    {
        return $this->form('users.create', new User());
    }

    public function store(CreateUserRequest $request)
    {
        $request->createUser();

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return $this->form('users.edit', $user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $request->updateUser($user);

        return redirect()->route('users.show', $user->id);
    }

    public function trash(User $user)
    {
        $user->profile()->delete();
        $user->delete();

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->firstOrFail();

        abort_unless($user->trashed(), 404);

        $user->forceDelete();

        return redirect()->route('users.trashed');
    }

    protected function form($view, User $user)
    {
        return view($view, [
            'professions' => Profession::orderBy('title', 'ASC')->get(),
            'skills' => Skill::orderBy('name', 'ASC')->get(),
            'roles' => trans('users.roles'),
            'user' => $user,
        ]);
    }
}
