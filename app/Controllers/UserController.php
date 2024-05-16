<?php namespace App\Controllers; 

class UserController extends BaseController
{
    public function index(){
        echo 'this is working';
        return view('user-login');
    }
}

?>