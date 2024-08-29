@extends('layouts.main_login')

@section('main_login')
    <section class="logo-section align-center">
        <img src="{{ asset('assets/img/logo/logo_transparent.png') }}" alt="Logo" class="logo">
    </section>
    <section>
        <section class="form-container">
            <form role="form" method="POST" action="{{ route('login_users') }}">
                @csrf
                <label style="color: #fefefe">Name</label>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="name" aria-label="name"
                        aria-describedby="name-addon" name="name">
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <label style="color: #fefefe">Password</label>
                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Password" aria-label="Password"
                        aria-describedby="password-addon" name="password">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="text-center">
                    <button type="submit" class="btn">Sign in</button>
                </div>
            </form>
        </section>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
        });
    </script>
@endpush
