<div>
    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if(session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header text-center">
            <h5 class="mb-0 text-center">
                <strong>OAuth Providers</strong>
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">Link your account with OAuth providers to enable alternative login methods.</p>
            
            @foreach($supportedProviders as $provider)
                @php
                    $isLinked = collect($linkedProviders)->contains('provider', $provider);
                    $displayName = $this->getProviderDisplayName($provider);
                    $icon = $this->getProviderIcon($provider);
                @endphp
                
                <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                    <div class="d-flex align-items-center">
                        <i class="{{ $icon }} me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h6 class="mb-0">{{ $displayName }}</h6>
                            <small class="text-muted">
                                @if($isLinked)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Linked</span>
                                @else
                                    <span class="text-muted">Not linked</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    <div>
                        @if($isLinked)
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    wire:click="confirmUnlink('{{ $provider }}')">
                                <i class="fas fa-unlink"></i> Unlink
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                    wire:click="linkProvider('{{ $provider }}')">
                                <i class="fas fa-link"></i> Link
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Unlink Confirmation Modal -->
    @if($showUnlinkConfirm)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Unlink</h5>
                        <button type="button" class="btn-close" wire:click="cancelUnlink()"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to unlink {{ $this->getProviderDisplayName($providerToUnlink) }} from your account?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Warning:</strong> Make sure you have a password set for your account to maintain access after unlinking OAuth providers.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelUnlink()">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="unlinkProvider()">Unlink</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('refresh_oauth'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Dispatching refreshOAuthProviders event');
                Livewire.dispatch('refreshOAuthProviders');
            });
        </script>
    @endif
</div>