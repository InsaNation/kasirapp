@extends('layouts.main_index_kasir')

@section('title', 'Arka Kasir')
@section('main_index')
    {{-- content --}}
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div>
                        <a href="{{ route('transaksi.create') }}" class="button custom-button" role="button"
                            style="border-radius: 10px;">
                            Tambah Transaksi
                        </a>
                    </div>
                    <br>

                    {{-- @include('partials.items.create_item') --}}
                    <div class="card mb-4 p">
                        <div class="card-header">
                            <h6>Data Transaksi (On Progress)</h6>
                        </div>
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice Code</th>
                                    <th>Total Barang</th>
                                    <th>Total Harga</th>
                                    <th>Bayar</th>
                                    <th>Kembalian</th>
                                    <th>Cashier</th>
                                </tr>
                            </thead>
                            <tbody id="transaction-table-body">
                                {{-- @foreach ($transaksi as $trans)
                                    <tr>
                                        <td>{{ $trans['invoice_code'] }}</td>
                                        <td>{{ $trans['total_items'] }}</td>
                                        <td>{{ $trans['total_price'] }}</td>
                                        <td>{{ $trans['change'] }}</td>
                                        <td>{{ $trans['bayar'] }}</td>
                                        <td>{{ $trans['cashier'] }}</td>
                                    </tr>
                                @endforeach --}}
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

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function showTransaksi() {
                fetch('http://127.0.0.1:8001/api/transaksi_kasir', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        },
                        credentials: 'include' // Include cookies in the request
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        const tableBody = document.getElementById('transaction-table-body');
                        tableBody.innerHTML = ''; // Clear any existing rows

                        data.forEach(transaction => {
                            const row = document.createElement('tr');

                            row.innerHTML = `
                    <td>${transaction.invoice_code}</td>
                    <td>${transaction.total_items}</td>
                    <td>${transaction.total_price}</td>
                    <td>${transaction.bayar}</td>
                    <td>${transaction.change}</td>
                    <td>${transaction.cashier}</td>
                `;

                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching transactions:', error);
                    });
            }

            showTransaksi();
        });
    </script> --}}

@endsection
