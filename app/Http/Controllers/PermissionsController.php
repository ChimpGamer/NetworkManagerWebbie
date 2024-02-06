<?php

namespace App\Http\Controllers;

use App\Models\Permissions\Group;
use Illuminate\View\View;

class PermissionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View {
        return view('permissions.index');
    }

    public function groupPermissions(Group $group): View
    {
        return view('permissions.group-permissions', ['group' => $group]);
    }

    public function groupPrefixes(Group $group): View
    {
        return view('permissions.group-prefixes', ['group' => $group]);
    }

    public function groupSuffixes(Group $group): View
    {
        return view('permissions.group-suffixes', ['group' => $group]);
    }
}
