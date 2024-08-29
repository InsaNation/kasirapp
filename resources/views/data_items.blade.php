@extends('layouts.main_index_admin')

@section('title', 'Data Items')
@section('main_index')
    {{-- content --}}
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div>
                        <a href="{{ route('item.create') }}" class="button custom-button" role="button"
                            style="border-radius: 10px;">
                            Tambah Items
                        </a>
                    </div>
                    <br>

                    {{-- @include('partials.items.create_item') --}}
                    <div class="card mb-4 p">
                        <div class="card-header">
                            <h6>Data Items</h6>
                        </div>
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    {{-- <th class="text-uppercase text-primary text-center">
                                        Image</th> --}}
                                    <th class="text-uppercase text-primary text-center">
                                        Nama</th>
                                    <th class="text-uppercase text-primary text-center">
                                        Items Code</th>
                                    <th class="text-uppercase text-primary text-center">
                                        Stock</th>
                                    <th class="text-uppercase text-primary text-center">
                                        Price</th>
                                    <th class="text-uppercase text-primary text-center">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    @if ($data['is_deleted'] == 0)
                                        <tr>
                                            {{-- <td>
                                                <img class="card-img-top" src="{{ $data['image'] }}" alt="image of {{ $data['name'] }}">
                                            </td> --}}
                                            <td>
                                                <p class="text-secondary text-center px-3">{{ $data['name'] }}</p>
                                            </td>
                                            <td>
                                                <p class="text-secondary text-center px-3">{{ $data['items_code'] }}</p>
                                            </td>
                                            <td>
                                                <p class="text-secondary text-center px-3">{{ $data['stock'] }}</p>
                                            </td>
                                            <td>
                                                <p class="text-secondary text-center px-3">{{ $data['price'] }}</p>
                                            </td>
                                            <td class="gap-3 text-center">
                                                <button type="button" class="btn bg-gradient-info" data-bs-toggle="modal"
                                                    data-bs-target="#editSiswa_{{ $data['id'] }}"
                                                    data-book-id="{{ $data['id'] }}">
                                                    Edit
                                                </button>
                                                @include('partials.items.update_item')
                                                <form id="deleteForm_{{ $data['id'] }}"
                                                    action="{{ route('item.delete', $data['id']) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="confirmDelete({{ $data['id'] }})">Delete</button>
                                                </form>
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
