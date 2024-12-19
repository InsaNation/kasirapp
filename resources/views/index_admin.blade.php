@extends('layouts.main_index_admin')

@section('title', 'Admin Dashboard')

@section('main_index')
    <div class="container" style="padding: 20px;">
        <h1 style="color: #000000">Dashboard</h1>
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card shadow-lg">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase fw-bold">Today's Incomes</p>
                                    <h5 class="fw-bolder">
                                        Rp. {{ number_format($daily_incomes, 0, ',', '.') }}
                                    </h5>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end d-flex justify-content-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle ">
                                    <i class="fa-solid fa-sack-dollar fa-2xl mt-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card shadow-lg">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase fw-bold">Today's Exchange</p>
                                    <h5 class="fw-bolder">
                                        {{ $daily_transaction }}
                                    </h5>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end d-flex justify-content-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle ">
                                    <i class="fa-solid fa-file-invoice fa-2xl mt-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card shadow-lg">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase fw-bold">Monthly Incomes</p>
                                    <h5 class="fw-bolder">
                                        Rp. {{ number_format($monthly_incomes, 0, ',', '.') }}
                                    </h5>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end d-flex justify-content-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle ">
                                    <i class="fa-solid fa-sack-dollar fa-2xl mt-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card shadow-lg">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase fw-bold">Monthly Exchange</p>
                                    <h5 class="fw-bolder">
                                        {{ $monthly_transactions }}
                                    </h5>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end d-flex justify-content-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle ">
                                    <i class="fa-solid fa-file-invoice fa-2xl mt-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <canvas id="myChart"></canvas>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            const ctx = document.getElementById('myChart');

            // Fetch data from your API
            fetch('http://localhost:8001/api/chart')
                .then(response => response.json())
                .then(data => {
                    // Extracting labels and data from the API response
                    const labels = data.data.map(item => item.M);
                    const counts = data.data.map(item => item.count);

                    // Create the chart
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Monthly Exchange',
                                data: counts,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }

                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        </script>
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
    @endpush
