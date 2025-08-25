<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserApprovalController extends Controller
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
     * Display a listing of users pending approval
     */
    public function index(Request $request)
    {
        $this->authorize('manage_groups_and_accounts');
        $query = User::query();
        
        // Filter by approval status
        $status = $request->get('status', 'pending');
        switch ($status) {
            case 'pending':
                $query->pendingApproval();
                break;
            case 'approved':
                $query->approved();
                break;
            case 'rejected':
                $query->rejected();
                break;
            case 'all':
                // No filter
                break;
            default:
                $query->pendingApproval();
        }
        
        // Search by username or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by OAuth provider
        if ($request->has('provider') && $request->provider) {
            $query->where('oauth_provider', $request->provider);
        }
        
        $users = $query->with(['approvedBy'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(20)
                      ->withQueryString();
        
        $pendingCount = User::pendingApproval()->count();
        $approvedCount = User::approved()->count();
        $rejectedCount = User::rejected()->count();
        
        return view('admin.user-approvals.index', compact(
            'users', 
            'status', 
            'pendingCount', 
            'approvedCount', 
            'rejectedCount'
        ));
    }
    
    /**
     * Show the specified user for approval
     */
    public function show(User $user)
    {
        $this->authorize('manage_groups_and_accounts');
        $user->load(['approvedBy']);
        return view('admin.user-approvals.show', compact('user'));
    }
    
    /**
     * Approve a user
     */
    public function approve(Request $request, User $user)
    {
        $this->authorize('manage_groups_and_accounts');
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        try {
            $user->approve(auth()->user(), $request->notes);
            
            // TODO: Send approval notification to user
            
            return redirect()->route('admin.user-approvals.index')
                           ->with('success', "User '{$user->username}' has been approved successfully!");
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to approve user: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Reject a user
     */
    public function reject(Request $request, User $user)
    {
        $this->authorize('manage_groups_and_accounts');
        $validator = Validator::make($request->all(), [
            'notes' => 'required|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        try {
            $user->reject(auth()->user(), $request->notes);
            
            // TODO: Send rejection notification to user
            
            return redirect()->route('admin.user-approvals.index')
                           ->with('success', "User '{$user->username}' has been rejected.");
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to reject user: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Bulk approve users
     */
    public function bulkApprove(Request $request)
    {
        $this->authorize('manage_groups_and_accounts');
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        try {
            $users = User::whereIn('id', $request->user_ids)
                        ->pendingApproval()
                        ->get();
            
            $approved = 0;
            foreach ($users as $user) {
                if ($user->approve(auth()->user(), $request->notes)) {
                    $approved++;
                }
            }
            
            return redirect()->route('admin.user-approvals.index')
                           ->with('success', "Successfully approved {$approved} user(s).");
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to bulk approve users: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Bulk reject users
     */
    public function bulkReject(Request $request)
    {
        $this->authorize('manage_groups_and_accounts');
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'notes' => 'required|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        try {
            $users = User::whereIn('id', $request->user_ids)
                        ->pendingApproval()
                        ->get();
            
            $rejected = 0;
            foreach ($users as $user) {
                if ($user->reject(auth()->user(), $request->notes)) {
                    $rejected++;
                }
            }
            
            return redirect()->route('admin.user-approvals.index')
                           ->with('success', "Successfully rejected {$rejected} user(s).");
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to bulk reject users: ' . $e->getMessage()]);
        }
    }
}