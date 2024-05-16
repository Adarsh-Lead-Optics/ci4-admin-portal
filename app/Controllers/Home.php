<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }


    public function about(){
        echo 'This is about Page';
        // return view('about');
    }

    public function mysession(){
        $mySession = session();
       $myArray = [
           'name'=>'Hero',
            'email'=>'hero@gmail.com',
            'mobile'=>9548053940,
            'password'=> 12345678
       ];
       $mySession->set($myArray);
    //    var_dump($myArray); 
    }

    // use session ' it's very best way to get and set or check the data 

    public function getSession(){
        
    }

}
