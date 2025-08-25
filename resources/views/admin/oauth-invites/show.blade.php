@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">OAuth Invite Details</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="generateInviteUrl({{ $oauthInvite->id }})">
                            <i class="fas fa-link"></i> Get URL
                        </button>
                        <a href="{{ route('admin.oauth-invites.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Invites
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @php
                        $status = 'Active';
                        $statusClass = 'success';
                        
                        if ($oauthInvite->expires_at < now()) {
                            $status = 'Expired';
                            $statusClass = 'danger';
                        } elseif ($oauthInvite->single_use && $oauthInvite->used_count > 0) {
                            $status = 'Used';
                            $statusClass = 'secondary';
                        } elseif ($oauthInvite->used_count >= $oauthInvite->max_uses) {
                            $status = 'Used Up';
                            $statusClass = 'secondary';
                        }
                    @endphp

                    <div class="row">
                        <!-- Invite Information -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Invite Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                <span class="badge bg-{{ $statusClass }}">{{ $status }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Invite Code:</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <code class="me-2">{{ $oauthInvite->code }}</code>
                                                    <button class="btn btn-link btn-sm p-0" 
                                                            onclick="copyToClipboard('{{ $oauthInvite->code }}')"
                                                            title="Copy code">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Email Restriction:</td>
                                            <td>
                                                @if($oauthInvite->email)
                                                    <span class="text-info">{{ $oauthInvite->email }}</span>
                                                @else
                                                    <span class="text-muted">Any email allowed</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Usage Type:</td>
                                            <td>
                                                @if($oauthInvite->single_use)
                                                    <span class="badge bg-info">Single Use</span>
                                                @else
                                                    <span class="badge bg-primary">Multiple Use</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Usage Count:</td>
                                            <td>
                                                <span class="{{ $oauthInvite->used_count >= $oauthInvite->max_uses ? 'text-danger' : 'text-success' }}">
                                                    {{ $oauthInvite->used_count }}
                                                </span>
                                                / {{ $oauthInvite->max_uses }}
                                                
                                                @if($oauthInvite->used_count >= $oauthInvite->max_uses)
                                                    <span class="badge bg-warning ms-1">Exhausted</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Created:</td>
                                            <td>
                                                {{ $oauthInvite->created_at->format('M j, Y \\a\\t H:i') }}
                                                <br>
                                                <small class="text-muted">({{ $oauthInvite->created_at->diffForHumans() }})</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Expires:</td>
                                            <td>
                                                {{ $oauthInvite->expires_at->format('M j, Y \\a\\t H:i') }}
                                                <br>
                                                <small class="text-{{ $oauthInvite->expires_at < now() ? 'danger' : 'success' }}">
                                                    ({{ $oauthInvite->expires_at->diffForHumans() }})
                                                </small>
                                            </td>
                                        </tr>
                                        @if($oauthInvite->creator)
                                            <tr>
                                                <td class="fw-bold">Created By:</td>
                                                <td>{{ $oauthInvite->creator->username }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Usage History -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Usage History</h6>
                                </div>
                                <div class="card-body">
                                    @if($oauthInvite->usedBy)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>User</th>
                                                        <th>Email</th>
                                                        <th>Used At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $oauthInvite->usedBy->username }}</td>
                                                        <td>{{ $oauthInvite->usedBy->email }}</td>
                                                        <td>
                                                            <small class="text-muted">
                                                                {{ $oauthInvite->used_at ? $oauthInvite->used_at->format('M j, H:i') : 'N/A' }}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">No users have used this invite yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button class="btn btn-primary btn-sm" onclick="generateInviteUrl({{ $oauthInvite->id }})">
                                            <i class="fas fa-link"></i> Generate Invite URL
                                        </button>
                                        
                                        <button class="btn btn-secondary btn-sm" onclick="copyToClipboard('{{ $oauthInvite->code }}')">
                                            <i class="fas fa-copy"></i> Copy Invite Code
                                        </button>
                                        
                                        @if($status === 'Active')
                                            <span class="badge bg-success">Invite is active and ready to use</span>
                                        @elseif($status === 'Expired')
                                            <span class="badge bg-danger">Invite has expired</span>
                                        @else
                                            <span class="badge bg-secondary">Invite has been used up</span>
                                        @endif
                                        
                                        <div class="ms-auto">
                                            <form action="{{ route('admin.oauth-invites.destroy', $oauthInvite) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Are you sure you want to delete this invite? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i> Delete Invite
                                                </button>
                                            </form>
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

<!-- URL Modal -->
<div class="modal fade" id="urlModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invite URL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">
                    Share this URL with users to allow them to register using this invite:
                </p>
                <div class="input-group">
                    <input type="text" class="form-control" id="inviteUrl" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyInviteUrl()">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Users can click this link to go directly to the login page with the invite code pre-filled.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show a temporary success message
        showToast('Copied to clipboard!', 'success');
    }).catch(function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('Copied to clipboard!', 'success');
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
        showToast('Failed to generate URL', 'error');
    });
}

function copyInviteUrl() {
    const urlInput = document.getElementById('inviteUrl');
    urlInput.select();
    navigator.clipboard.writeText(urlInput.value).then(function() {
        showToast('URL copied to clipboard!', 'success');
    });
}

function showToast(message, type = 'info') {
    // Simple toast implementation - you can replace with your preferred toast library
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 3000);
}
</script>
@endsection