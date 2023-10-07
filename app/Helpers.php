<?php

use App\Models\User;

function currentUser(): ?User
{
    return auth()->user();
}
