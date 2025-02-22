<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DiscountMail;


class UserController extends Controller
{
    public function index()
    {
        return view('pages.add_user');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'gender' => 'required',
            'mobile' => 'required|numeric',
        ]);


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'mobile' => $request->mobile,
        ]);

        return back()->with('success', 'User added successfully!');
    }


    public function count()
    {
        $count = User::count();
        return response()->json(['count' => $count]);
    }


    public function userlist()
    {
        return view('pages.user_list');
    }

    public function gerusers()
    {
        $users = User::all();

        $nameFilter = request('name');
        $emailFilter = request('email');


        if (!empty($nameFilter)) {
            $users = $users->filter(function ($user) use ($nameFilter) {
                return stripos($user->name, $nameFilter) !== false;
            });
        }

        if (!empty($emailFilter)) {
            $users = $users->filter(function ($user) use ($emailFilter) {
                return stripos($user->email, $emailFilter) !== false;
            });
        }

        return response()->json(['data' => $users->values()]);
    }


    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => 'User deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'mobile' => $request->mobile
        ]);

        return response()->json(['message' => 'User updated successfully']);
    }




    public function getdiscount(Request $request)
    {
        $email = $request->input('email'); // Get the email from request
        Mail::to($email)->send(new DiscountMail());
        return response()->json(['message' => 'Discount email sent successfully']);
    }

}
