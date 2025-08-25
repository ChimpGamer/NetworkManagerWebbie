@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User Approval Details</h5>
                    <a href="{{ route('admin.user-approvals.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- User Information -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">User Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            @if($user->avatar)
                                                <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle mb-3" width="80" height="80">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                                    <i class="fas fa-user fa-2x text-white"></i>
                                                </div>
                                            @endif
                                            <h6>{{ $user->username }}</h6>
                                            @if($user->approval_status === 'pending')
                                                <span class="badge bg-warning">Pending Approval</span>
                                            @elseif($user->approval_status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($user->approval_status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Username:</strong></td>
                                                    <td>{{ $user->username }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email:</strong></td>
                                                    <td>{{ $user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>OAuth Providers:</strong></td>
                                                    <td>
                                                        @php
                                                            $linkedProviders = $user->oauthProviders;
                                                        @endphp
                                                        @if($linkedProviders->count() > 0)
                                                            @foreach($linkedProviders as $provider)
                                                                <span class="badge bg-info me-1">{{ ucfirst($provider->provider) }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="badge bg-secondary">Local Account</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Provider Details:</strong></td>
                                                    <td>
                                                        @if($linkedProviders->count() > 0)
                                                            @foreach($linkedProviders as $provider)
                                                                <div class="mb-1">
                                                                    <strong>{{ ucfirst($provider->provider) }}:</strong> {{ $provider->provider_id }}
                                                                    <small class="text-muted">({{ $provider->provider_email }})</small>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>User Group:</strong></td>
                                                    <td><span class="badge bg-primary">{{ $user->usergroup }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Account Status:</strong></td>
                                                    <td>
                                                        @if($user->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Registration Date:</strong></td>
                                                    <td>{{ $user->created_at->format('F j, Y \\a\\t g:i A') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Last Login:</strong></td>
                                                    <td>{{ $user->last_login ? $user->last_login->format('F j, Y \\a\\t g:i A') : 'Never' }}</td>
                                                </tr>
                                                @if($user->approved_at)
                                                    <tr>
                                                        <td><strong>Approved At:</strong></td>
                                                        <td>{{ $user->approved_at->format('F j, Y \\a\\t g:i A') }}</td>
                                                    </tr>
                                                @endif
                                                @if($user->approvedBy)
                                                    <tr>
                                                        <td><strong>Approved By:</strong></td>
                                                        <td>{{ $user->approvedBy->username }}</td>
                                                    </tr>
                                                @endif
                                                @if($user->approval_notes)
                                                    <tr>
                                                        <td><strong>Approval Notes:</strong></td>
                                                        <td>{{ $user->approval_notes }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Actions</h6>
                                </div>
                                <div class="card-body">
                                    @if($user->approval_status === 'pending')
                                        <!-- Approval Form -->
                                        <form method="POST" action="{{ route('admin.user-approvals.approve', $user) }}" class="mb-3">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="approve_notes" class="form-label">Approval Notes (Optional)</label>
                                                <textarea name="notes" id="approve_notes" class="form-control" rows="3" placeholder="Add any notes about this approval..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to approve this user?')">
                                                <i class="fas fa-check"></i> Approve User
                                            </button>
                                        </form>

                                        <!-- Rejection Form -->
                                        <form method="POST" action="{{ route('admin.user-approvals.reject', $user) }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="reject_notes" class="form-label">Rejection Reason</label>
                                                <textarea name="notes" id="reject_notes" class="form-control" rows="3" placeholder="Explain why this user is being rejected..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to reject this user?')">
                                                <i class="fas fa-times"></i> Reject User
                                            </button>
                                        </form>
                                    @else
                                        <div class="text-center">
                                            <p class="text-muted">This user has already been {{ $user->approval_status }}.</p>
                                            @if($user->approval_status === 'approved')
                                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                            @else
                                                <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Additional Actions -->
                                    <hr>
                                    <div class="d-grid gap-2">
                                        @if($linkedProviders->count() > 0)
                                            @foreach($linkedProviders as $provider)
                                                <a href="#" class="btn btn-outline-info btn-sm mb-1" onclick="copyToClipboard('{{ $provider->provider_id }}')">
                                                    <i class="fas fa-copy"></i> Copy {{ ucfirst($provider->provider) }} ID
                                                </a>
                                            @endforeach
                                        @endif
                                        <a href="#" class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard('{{ $user->email }}')">
                                            <i class="fas fa-envelope"></i> Copy Email
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Statistics -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Account Statistics</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h4 class="text-primary">{{ $user->created_at->diffInDays() }}</h4>
                                                <small class="text-muted">Days Since Registration</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-info">{{ $user->last_login ? $user->last_login->diffInDays() : 'Never' }}</h4>
                                            <small class="text-muted">Days Since Last Login</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // You could add a toast notification here
        alert('Copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection