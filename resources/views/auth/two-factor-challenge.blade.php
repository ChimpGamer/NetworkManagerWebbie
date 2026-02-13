@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center p-4">
                    <img src="{{ asset('images/full_logo.png') }}" class="img-fluid" alt="Logo">
                </div>

                @error('code')
                <div class="row mb-0" style="padding-left: 12px; padding-right: 12px">
                    <span class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                </div>
                @enderror

                <div class="card">
                    <div class="card-header">Two-Factor Authentication</div>

                    <div class="card-body">
                        <form method="POST" action="/two-factor-challenge">
                            @csrf

                            <div class="row mb-2">
                                <label for="code" class="col-lg-4 col-form-label text-lg-end">Authentication Code</label>

                                <div class="col-lg-8">
                                    <input type="text" name="code" class="form-control  @error('code') is-invalid @enderror" autofocus>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Verify</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
