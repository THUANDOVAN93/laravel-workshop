<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(): View
    {
        return view('groups.index', [
            'groups' => Group::with('users')->get(),
            'users' => User::all(),
        ]);
    }

    public function addUser(Request $request, Group $group): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $group->users()->syncWithoutDetaching([$request->user_id]);

        return redirect()->route('groups.index');
    }
}
