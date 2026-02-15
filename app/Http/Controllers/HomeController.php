<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    //
    public function viewResult()
    {
        if (Session::has('result')) {
            $result = Session::get('result');
            return view('result', compact('result'));
        } else {
            $notification = array(
                'message' => 'Votre session a expirée.!',
                'alert-type' => 'error'
            );
            return redirect('/')->with($notification);
        }
    }
}
