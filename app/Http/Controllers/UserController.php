<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Product;

class UserController extends Controller
{

    //Test Controller-Function
    function test($id, $email)
    {
        return $user = User::where('id', $id)
            ->where('email', $email)
            ->get();
    }


    function register(Request $req)
    {

        $user = new User;
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->password = Hash::make($req->input('password'));
        $user->is_emailconfirmed = '0';
        $user->save();

        $this->htmlEmail($user);

        return $user;

    }

    function fakeUser()
    {

        $user = factory(User::class)->make();
        return $user;
    }

    function login(Request $req)
    {

        $user = User::where('email', $req->email)->first();

        if ($user && Hash::check($req->password, $user->password)) {
            return $user;
        }


        return ["Error" => "Email or Password is not match!"];
    }

    function getUserDetails($id, $email)    {

        $user = User::where('id', $id)
            ->where('email', $email)
            ->first();

        if (!$user) {

            return 'User not found!';
        } 
        else {


            $user->is_emailconfirmed = '1';
            $user->save();
            return $user;
        }
    }

    function basicEmail()
    {
        $data = array(
            'name' => "Virat Gandhi",
            'address' =>
            [
                'street' => "Virat Gandhi",
                'building' => "Virat Gandhi"
            ]
        );

        Mail::send(
            ['text' => 'emailconfirmation'],
            $data,
            function ($message) {
                $message
                    ->to('reuel@axadra.com', 'Tutorials Point')
                    ->subject('Laravel Basic Testing Mail')
                    ->from('no-reply@gmail.com', 'Virat Gandhi');
            }
        );
        return "Basic Email Sent. Check your inbox.";
    }

    function htmlEmail($user)
    {
        $data = array(
            'id' => $user->id,
            'email' => $user->email,
            'name' => "Virat Gandhi",
            'address' =>
            [
                'street' => "Virat Gandhi",
                'building' => "Virat Gandhi"
            ]
        );

        Mail::send(
            'emailconfirmation',
            $data,
            function ($message) {
                $message
                    ->to('reuel@axadra.com', 'Tutorials Point')
                    ->subject('Laravel Basic Testing Mail')
                    ->from('no-reply@gmail.com', 'Virat Gandhi');
            }
        );
        return "HTML Email Sent. Check your inbox.";
    }


    function htmlWithAttachmentEmail()
    {
        $data = array(
            'name' => "Virat Gandhi",
            'address' =>
            [
                'street' => "Virat Gandhi",
                'building' => "Virat Gandhi"
            ]
        );

        Mail::send(
            'emailconfirmation',
            $data,
            function ($message) {
                $message
                    ->to('reuel@axadra.com', 'Tutorials Point')
                    ->subject('Laravel Basic Testing Mail')
                    ->from('no-reply@gmail.com', 'Virat Gandhi')
                    ->attach(public_path('storage\image01.jpg'))
                    ->attach(public_path('products\video01.mp4'));
            }
        );
        return "HTML Email with Attachment Sent. Check your inbox.";
    }
}
