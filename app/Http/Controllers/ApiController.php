<?php

namespace App\Http\Controllers;

use App\Models\Insta10kFollow;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\InstaAccount;
use Illuminate\Support\Str;
use DataTables;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function insta_account()
    {
        $accounts = InstaAccount::paginate(9);
        $accounts->getCollection()->transform(function ($account) {
            $account->image = asset('uploads/insta_account') . '/' . $account->image;
            return $account;
        });
        return $accounts;
    }
    public function insta_account_random()
    {
        $accounts = InstaAccount::inRandomOrder()->limit(4)->get();
        $accounts->transform(function ($account) {
            $account->image = asset('uploads/insta_account') . '/' . $account->image;
            return $account;
        });
        return $accounts;
    }
    public function insta_10k_follow()
    {
        $accounts = Insta10kFollow::orderBy('created_at', 'desc')->paginate(9);
        return $accounts;
    }
    public function save_insta_10k_follow(Request $request)
    {
        // Save the data into the database
        $instaFollow = new Insta10kFollow();
        $instaFollow->name = $request['name'];
        $instaFollow->username = $request['username'];
        $instaFollow->whatsapp = $request['whatsapp'];
        $instaFollow->added_from = 'api';
        $instaFollow->added = rand(5000,9000);
        $instaFollow->save();
        // Return a response indicating success
        return response()->json(['message' => 'Data saved successfully', 'status' => true], 201);
    }
}
