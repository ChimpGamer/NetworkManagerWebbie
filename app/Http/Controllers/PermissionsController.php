<?php

namespace App\Http\Controllers;

use App\Models\Permissions\Group;
use Illuminate\Auth\Access\AuthorizationException;
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

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view_permissions');

        return view('permissions.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function groupPermissions(Group $group): View
    {
        $this->authorize('view_permissions');
        return view('permissions.group-permissions', ['group' => $group]);
    }

    /**
     * @throws AuthorizationException
     */
    public function groupPrefixes(Group $group): View
    {
        $this->authorize('view_permissions');
        return view('permissions.group-prefixes', ['group' => $group]);
    }

    /**
     * @throws AuthorizationException
     */
    public function groupSuffixes(Group $group): View
    {
        $this->authorize('view_permissions');
        return view('permissions.group-suffixes', ['group' => $group]);
    }

    /**
     * @throws AuthorizationException
     */
    public function groupParents(Group $group): View
    {
        $this->authorize('view_permissions');
        return view('permissions.group-parents', ['group' => $group]);
    }

    /**
     * @throws AuthorizationException
     */
    public function groupMembers(Group $group): View
    {
        $this->authorize('view_permissions');
        return view('permissions.group-members', ['group' => $group]);
    }
}
