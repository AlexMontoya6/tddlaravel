<?php

namespace App;

use App\QueryBuilder;

class UserQuery extends QueryBuilder
{
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }


    public function withLastLogin()
    {
        $subselect = Login::select('logins.created_at')
            ->whereColumn('logins.user_id', 'users.id')
            ->latest()
            ->limit(1);


        return $this->addSelect([
            'last_login_at' => $subselect,
        ]);

    }
}