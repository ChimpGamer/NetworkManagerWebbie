<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OAuthInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OAuthInviteController extends Controller
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
     * Display a listing of OAuth invites
     */
    public function index(Request $request)
    {
        $this->authorize('manage_groups_and_accounts');
        $query = OAuthInvite::query();
        
        // Filter by status
        if ($request->has('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('expires_at', '>', now())
                          ->where(function($q) {
                              $q->where('used_count', '<', 'max_uses')
                                ->orWhere('single_use', false);
                          });
                    break;
                case 'expired':
                    $query->where('expires_at', '<=', now());
                    break;
                case 'used':
                    $query->where('used_count', '>=', 'max_uses');
                    break;
            }
        }
        
        // Search by email or code
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        $invites = $query->orderBy('created_at', 'desc')
                        ->paginate(20)
                        ->withQueryString();
        
        return view('admin.oauth-invites.index', compact('invites'));
    }
    
    /**
     * Show the form for creating a new OAuth invite
     */
    public function create()
    {
        $this->authorize('manage_groups_and_accounts');
        
        return view('admin.oauth-invites.create');
    }
    
    /**
     * Store a newly created OAuth invite
     */
    public function store(Request $request)
    {
        $this->authorize('manage_groups_and_accounts');
        
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|max:255',
            'expires_hours' => 'required|integer|min:1|max:8760', // Max 1 year
            'max_uses' => 'required|integer|min:1|max:1000',
            'single_use' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        try {
            $invite = OAuthInvite::createInvite(
                email: $request->email,
                expiresAt: now()->addHours($request->expires_hours),
                singleUse: $request->boolean('single_use'),
                maxUses: $request->single_use ? 1 : $request->max_uses,
                createdBy: auth()->id()
            );
            
            return redirect()->route('admin.oauth-invites.index')
                           ->with('success', 'OAuth invite created successfully!');
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to create invite: ' . $e->getMessage()])
                           ->withInput();
        }
    }
    
    /**
     * Display the specified OAuth invite
     */
    public function show(OAuthInvite $oauthInvite)
    {
        $this->authorize('manage_groups_and_accounts');
        
        $oauthInvite->load(['creator', 'usedBy']);
        return view('admin.oauth-invites.show', compact('oauthInvite'));
    }
    
    /**
     * Remove the specified OAuth invite
     */
    public function destroy(OAuthInvite $oauthInvite)
    {
        $this->authorize('manage_groups_and_accounts');
        
        try {
            $oauthInvite->delete();
            return redirect()->route('admin.oauth-invites.index')
                           ->with('success', 'OAuth invite deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to delete invite: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Bulk cleanup expired invites
     */
    public function cleanup()
    {
        $this->authorize('manage_groups_and_accounts');
        
        try {
            $deleted = OAuthInvite::where('expires_at', '<', now())->delete();
            return redirect()->route('admin.oauth-invites.index')
                           ->with('success', "Cleaned up {$deleted} expired invite(s).");
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to cleanup invites: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Generate invite URL
     */
    public function generateUrl(OAuthInvite $oauthInvite)
    {
        $this->authorize('manage_groups_and_accounts');
        
        $url = url('/login?invite_code=' . $oauthInvite->code);
        
        return response()->json([
            'success' => true,
            'url' => $url
        ]);
    }
}