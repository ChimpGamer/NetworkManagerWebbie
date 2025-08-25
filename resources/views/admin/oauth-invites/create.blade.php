@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create OAuth Invite</h5>
                    <a href="{{ route('admin.oauth-invites.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Invites
                    </a>
                </div>
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.oauth-invites.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        Email Address 
                                        <small class="text-muted">(Optional)</small>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           placeholder="user@example.com">
                                    <div class="form-text">
                                        If specified, only this email address can use the invite. Leave empty to allow any email.
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expires_hours" class="form-label">
                                        Expires In (Hours) 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('expires_hours') is-invalid @enderror" 
                                            id="expires_hours" 
                                            name="expires_hours">
                                        <option value="1" {{ old('expires_hours') == '1' ? 'selected' : '' }}>1 Hour</option>
                                        <option value="6" {{ old('expires_hours') == '6' ? 'selected' : '' }}>6 Hours</option>
                                        <option value="24" {{ old('expires_hours') == '24' ? 'selected' : '' }}>1 Day</option>
                                        <option value="72" {{ old('expires_hours') == '72' ? 'selected' : '' }}>3 Days</option>
                                        <option value="168" {{ old('expires_hours', '168') == '168' ? 'selected' : '' }}>1 Week</option>
                                        <option value="336" {{ old('expires_hours') == '336' ? 'selected' : '' }}>2 Weeks</option>
                                        <option value="720" {{ old('expires_hours') == '720' ? 'selected' : '' }}>1 Month</option>
                                        <option value="custom" {{ old('expires_hours') && !in_array(old('expires_hours'), ['1', '6', '24', '72', '168', '336', '720']) ? 'selected' : '' }}>Custom</option>
                                    </select>
                                    <input type="number" 
                                           class="form-control mt-2 d-none" 
                                           id="custom_expires_hours" 
                                           name="custom_expires_hours" 
                                           value="{{ old('custom_expires_hours') }}"
                                           min="1" 
                                           max="8760" 
                                           placeholder="Enter hours (1-8760)">
                                    <div class="form-text">
                                        How long the invite will remain valid.
                                    </div>
                                    @error('expires_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="single_use" 
                                               name="single_use" 
                                               value="1"
                                               {{ old('single_use', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="single_use">
                                            Single Use Only
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        If checked, the invite can only be used once. Otherwise, it can be used multiple times up to the maximum.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3" id="max_uses_container" style="{{ old('single_use', true) ? 'display: none;' : '' }}">
                                    <label for="max_uses" class="form-label">
                                        Maximum Uses 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('max_uses') is-invalid @enderror" 
                                           id="max_uses" 
                                           name="max_uses" 
                                           value="{{ old('max_uses', '1') }}"
                                           min="1" 
                                           max="1000"
                                           placeholder="1">
                                    <div class="form-text">
                                        Maximum number of times this invite can be used (1-1000).
                                    </div>
                                    @error('max_uses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> 
                            The invite will be created with a unique code that can be shared with users. 
                            Users can use this code during OAuth registration to bypass any registration restrictions.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.oauth-invites.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Invite
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const expiresSelect = document.getElementById('expires_hours');
    const customInput = document.getElementById('custom_expires_hours');
    const singleUseCheckbox = document.getElementById('single_use');
    const maxUsesContainer = document.getElementById('max_uses_container');
    const maxUsesInput = document.getElementById('max_uses');

    // Handle custom expires hours
    expiresSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customInput.classList.remove('d-none');
            customInput.required = true;
            customInput.name = 'expires_hours';
            expiresSelect.name = 'expires_hours_select';
        } else {
            customInput.classList.add('d-none');
            customInput.required = false;
            customInput.name = 'custom_expires_hours';
            expiresSelect.name = 'expires_hours';
        }
    });

    // Handle single use checkbox
    singleUseCheckbox.addEventListener('change', function() {
        if (this.checked) {
            maxUsesContainer.style.display = 'none';
            maxUsesInput.value = '1';
            maxUsesInput.required = false;
        } else {
            maxUsesContainer.style.display = 'block';
            maxUsesInput.required = true;
            if (maxUsesInput.value === '1') {
                maxUsesInput.value = '5';
            }
        }
    });

    // Initialize on page load
    if (expiresSelect.value === 'custom') {
        customInput.classList.remove('d-none');
        customInput.required = true;
        customInput.name = 'expires_hours';
        expiresSelect.name = 'expires_hours_select';
    }
});
</script>
@endsection