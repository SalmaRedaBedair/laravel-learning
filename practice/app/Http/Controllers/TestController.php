<?php

namespace App\Http\Controllers;

use App\test\Mailer;
use App\test\UserMailer;

class TestController extends Controller
{
    public function test()
    {
        Route::get('dogs', function (Request $request) {
            $query = Dog::query();
            $query->when(request()->filled('filter'), function ($query) {
                $filters = explode(',', request('filter'));
                foreach ($filters as $filter) {
                    [$criteria, $value] = explode(':', $filter);
                    $query->where($criteria, $value);
                }
                return $query;
            });
            return $query->paginate(20);
        });
//        $object = app(Mailer::class);
//        echo $object->hello('Salma');
//        $object2 = app(Mailer::class);
//        echo $object2->hello('loma');
        dd(\Facades\App\test\UserMailer::hello());
    }
}
