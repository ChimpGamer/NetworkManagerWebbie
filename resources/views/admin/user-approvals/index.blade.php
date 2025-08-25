@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">OAuth User Approvals</h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning">{{ $pendingCount }} Pending</span>
                        <span class="badge bg-success">{{ $approvedCount }} Approved</span>
                        <span class="badge bg-danger">{{ $rejectedCount }} Rejected</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <form method="GET" action="{{ route('admin.user-approvals.index') }}">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                                </select>
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('provider'))
                                    <input type="hidden" name="provider" value="{{ request('provider') }}">
                                @endif
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form method="GET" action="{{ route('admin.user-approvals.index') }}">
                                <select name="provider" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Providers</option>
                                    <option value="google" {{ request('provider') === 'google' ? 'selected' : '' }}>Google</option>
                                    <option value="github" {{ request('provider') === 'github' ? 'selected' : '' }}>GitHub</option>
                                    <option value="discord" {{ request('provider') === 'discord' ? 'selected' : '' }}>Discord</option>
                                </select>
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.user-approvals.index') }}" class="d-flex">
                                <input type="text" name="search" class="form-control" placeholder="Search by username or email..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary ms-2">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if(request('provider'))
                                    <input type="hidden" name="provider" value="{{ request('provider') }}">
                                @endif
                            </form>
                        </div>
                    </div>

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

                    <!-- Bulk Actions -->
                    @if($status === 'pending' && $users->count() > 0)
                        <div class="mb-3">
                            <form id="bulkForm" method="POST">
                                @csrf
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                    <label for="selectAll" class="form-check-label me-3">Select All</label>
                                    <button type="button" class="btn btn-success btn-sm" onclick="bulkAction('approve')">
                                        <i class="fas fa-check"></i> Bulk Approve
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="bulkAction('reject')">
                                        <i class="fas fa-times"></i> Bulk Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @if($status === 'pending')
                                        <th width="50"></th>
                                    @endif
                                    <th>Avatar</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Provider</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    @if($status !== 'pending')
                                        <th>Approved By</th>
                                        <th>Approved At</th>
                                    @endif
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        @if($status === 'pending')
                                            <td>
                                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-check-input user-checkbox">
                                            </td>
                                        @endif
                                        <td>
                                            @if($user->avatar)
                                                <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle" width="32" height="32">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @php
                                                $linkedProviders = $user->oauthProviders->pluck('provider')->toArray();
                                            @endphp
                                            @if(count($linkedProviders) > 0)
                                                @foreach($linkedProviders as $provider)
                                                    <span class="badge bg-info me-1">{{ ucfirst($provider) }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge bg-secondary">Local</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->approval_status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($user->approval_status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($user->approval_status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('M j, Y H:i') }}</td>
                                        @if($status !== 'pending')
                                            <td>{{ $user->approvedBy ? $user->approvedBy->username : '-' }}</td>
                                            <td>{{ $user->approved_at ? $user->approved_at->format('M j, Y H:i') : '-' }}</td>
                                        @endif
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.user-approvals.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($user->approval_status === 'pending')
                                                    <form method="POST" action="{{ route('admin.user-approvals.approve', $user) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this user?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.user-approvals.reject', $user) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this user?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $status === 'pending' ? '10' : '9' }}" class="text-center py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No users found matching your criteria.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Select all functionality
const selectAllElement = document.getElementById('selectAll');
if (selectAllElement) {
    selectAllElement.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
}

// Bulk actions
function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one user.');
        return;
    }
    
    const form = document.getElementById('bulkForm');
    const actionUrl = action === 'approve' 
        ? '{{ route("admin.user-approvals.bulk-approve") }}'
        : '{{ route("admin.user-approvals.bulk-reject") }}';
    
    if (confirm(`Are you sure you want to ${action} ${checkedBoxes.length} user(s)?`)) {
        form.action = actionUrl;
        form.submit();
    }
}
</script>
@endsection