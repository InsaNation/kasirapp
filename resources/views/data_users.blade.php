@extends('layouts.main_index_admin')
@section('title', 'Data Items')
@section('main_index')
    {{-- content --}}
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <button type="button" class="button mb-4 custom-button" style="border-radius: 10px;" data-bs-toggle="modal"
                        data-bs-target="#createModal">
                        Tambah Akun
                    </button>
                    {{-- @include('partials.acc.create_admin') --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Data Members</h6>
                        </div>
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-primary text-center">
                                        Nama</th>
                                    <th class="text-uppercase text-primary text-center">
                                        Email</th>
                                    <th class="text-uppercase text-primary text-center">
                                        Members ID</th>
                                    <th class="text-uppercase text-primary text-center">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    @if ($data['is_deleted'] == 0)
                                        <tr>
                                            <td>
                                                <p class="text-secondary text-center px-3">{{ $data['name'] }}</p>
                                            </td>
                                            <td>
                                                <p class="text-secondary text-center px-3">{{ $data['email'] }}</p>
                                            </td>
                                            <td>
                                                <p class="text-secondary text-center px-3">{{ $data['membersID'] }}</p>
                                            </td>
                                            <td class="gap-3 text-center">
                                                @if ($data['role'] !== 'admin')
                                                    <button type="button" class="btn bg-gradient-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editSiswa_{{ $data['id'] }}"
                                                        data-book-id="{{ $data['id'] }}">
                                                        Edit
                                                    </button>
                                                    {{-- @include('partials.modals.edit_admin') --}}
                                                    <form id="deleteForm_{{ $data['id'] }}" action="" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger"
                                                            onclick="confirmDelete({{ $data['id'] }})">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        // Check if there are any error messages from Laravel validation
        @if ($errors->any())
            // Loop through each error message and display it using SweetAlert
            @foreach ($errors->all() as $error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ $error }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endforeach
        @elseif (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#logout-link').click(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be logged out!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, logout!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#logout-form').submit();
                    }
                })
            });
        });
    </script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm_' + id).submit();
                }
            })
        }
    </script>
@endsection
