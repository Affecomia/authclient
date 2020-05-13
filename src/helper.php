<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('team')) :
    // This function is kind-of fake.
    // For now, a user can only be part of one team.
    // Eventually would hold the team that the user is working in at that time.
    function team()
    {
        $teams = Auth::user()->teams();
        return $teams[0];
    }

endif;
