@extends('layouts.app')

@section('content')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="row">
        <div class="col-6">
            @livewire('accounts.show-account-groups')
        </div>

        <div class="col-6">
            @livewire('accounts.show-accounts')
        </div>
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#addAccountGroupModal').modal('hide');
            $('#addAccountModal').modal('hide');
            $('#editAccountModal').modal('hide');
            $('#editAccountGroupModal').modal('hide');
        });
    </script>
@endsection
