@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">OAuth Invites Management</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.oauth-invites.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create Invite
                        </a>
                        <form action="{{ route('admin.oauth-invites.cleanup') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm" 
                                    onclick="return confirm('Are you sure you want to cleanup all expired invites?')">
                                <i class="fas fa-trash"></i> Cleanup Expired
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.oauth-invites.index') }}" class="d-flex gap-2">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Used Up</option>
                                </select>
                                <input type="text" name="search" class="form-control form-control-sm" 
                                       placeholder="Search by email or code..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request()->hasAny(['status', 'search']))
                                    <a href="{{ route('admin.oauth-invites.index') }}" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
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

                    <!-- Invites Table -->
                    @if($invites->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Code</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Uses</th>
                                        <th>Expires</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invites as $invite)
                                        @php
                                            $status = 'Active';
                                            $statusClass = 'success';
                                            
                                            if ($invite->expires_at < now()) {
                                                $status = 'Expired';
                                                $statusClass = 'danger';
                                            } elseif ($invite->single_use && $invite->used_count > 0) {
                                                $status = 'Used';
                                                $statusClass = 'secondary';
                                            } elseif ($invite->used_count >= $invite->max_uses) {
                                                $status = 'Used Up';
                                                $statusClass = 'secondary';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <code class="text-muted">{{ substr($invite->code, 0, 12) }}...</code>
                                                <button class="btn btn-link btn-sm p-0 ms-1" 
                                                        onclick="copyToClipboard('{{ $invite->code }}')"
                                                        title="Copy full code">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </td>
                                            <td>{{ $invite->email ?? 'Any email' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $statusClass }}">{{ $status }}</span>
                                            </td>
                                            <td>{{ $invite->used_count }}/{{ $invite->max_uses }}</td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $invite->expires_at->format('M j, Y H:i') }}
                                                    <br>
                                                    <span class="text-{{ $invite->expires_at < now() ? 'danger' : 'success' }}">
                                                        ({{ $invite->expires_at->diffForHumans() }})
                                                    </span>
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $invite->created_at->format('M j, Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.oauth-invites.show', $invite) }}" 
                                                       class="btn btn-outline-info btn-sm" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-outline-primary btn-sm" 
                                                            onclick="generateInviteUrl({{ $invite->id }})" 
                                                            title="Generate URL">
                                                        <i class="fas fa-link"></i>
                                                    </button>
                                                    <form action="{{ route('admin.oauth-invites.destroy', $invite) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                onclick="return confirm('Are you sure you want to delete this invite?')"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $invites->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No OAuth invites found</h5>
                            <p class="text-muted">Create your first invite to get started.</p>
                            <a href="{{ route('admin.oauth-invites.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Invite
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- URL Modal -->
<div class="modal fade" id="urlModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invite URL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <input type="text" class="form-control" id="inviteUrl" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyInviteUrl()">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // You could add a toast notification here
        console.log('Copied to clipboard');
    });
}

function generateInviteUrl(inviteId) {
    fetch(`/admin/oauth-invites/${inviteId}/url`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('inviteUrl').value = data.url;
            const modal = new mdb.Modal(document.getElementById('urlModal'));
            modal.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to generate URL');
    });
}

function copyInviteUrl() {
    const urlInput = document.getElementById('inviteUrl');
    urlInput.select();
    navigator.clipboard.writeText(urlInput.value).then(function() {
        // You could add a toast notification here
        console.log('URL copied to clipboard');
    });
}
</script>
@endsection