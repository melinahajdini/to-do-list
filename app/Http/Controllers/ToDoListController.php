<?php

namespace App\Http\Controllers;

use App\Mail\TwoDaysBeforeExpireMail;
use App\Mail\WelcomeMail;
use App\Models\GymMember;
use App\Models\ToDo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ToDoListController extends \Illuminate\Routing\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {

        $userId = Auth::id();

        $todos = ToDo::where('user_id', "=", $userId)->latest()->get();

        return view('todos.index', ['todos' => $todos]);
    }

    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $attributions = $request->only('title', 'description', 'deletion_date');

        $attributions['user_id'] = $userId;
        if ($picture = $request->file('picture')) {
            $path = str_replace("public/", "", $picture->store('public/'));
            $attributions['picture'] = $path;
        }
        $todo = ToDo::create($attributions);

        $email = $user->email;

        $when = now()->addMinutes(2);
        Mail::to($email)
            ->later($when, new WelcomeMail());

        return redirect('/')->with('todo', $todo);
    }


    public function destroy($id)
    {

        $todo = ToDo::findorfail($id);
        $todo->delete();
        return redirect('/todos');
    }

}
