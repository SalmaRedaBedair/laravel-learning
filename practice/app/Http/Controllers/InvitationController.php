<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function confirm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired signature');
        }
        return 'Thank you for confirming!';
    }
    
}
