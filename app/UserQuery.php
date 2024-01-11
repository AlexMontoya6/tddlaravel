<?php

namespace App;

use App\QueryBuilder;

class UserQuery extends QueryBuilder
{
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}