<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transaksi Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid py-4">
        <h1>Transaksi</h1>

        <table>
            <tr>
                <td>
                    <div class="dropdown">
                        <input type="text" class="form-control" name="search" placeholder="Cari Produk"
                            id="search-input">
                        <div class="dropdown-menu show" aria-labelledby="dropdownMenuButton" id="search-results">
                            <!-- Results will be appended here by JavaScript -->
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="row mt-2">
            <div class="col-8">
                <div class="card border-primary">
                    <div class="card-body">
                        <h4 class="card-title">
                            No Invoice:
                            <span id="invoice-number">
                                @isset($invoiceNumber)
                                    {{ $invoiceNumber }}
                                @else
                                    Not Available
                                @endisset
                            </span>
                        </h4>
                        <table id="product-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be added here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <h4 class="card-title">Total Biaya</h4>
                        <div class="d-flex justify-content-between">
                            <span>Rp. </span>
                            <span id="total-cost">0</span>
                        </div>
                    </div>
                </div>
                <div class="card border-primary mt-2">
                    <div class="card-body">
                        <h4 class="card-title">Bayar</h4>
                        <input type="number" class="form-control" placeholder="Bayar" id="amount-paid">
                    </div>
                </div>
                <div class="card border-primary mt-2">
                    <div class="card-body">
                        <h4 class="card-title">Kembalian</h4>
                        <div class="d-flex justify-content-between">
                            <span>Rp. </span>
                            <span id="change">0</span>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success mt-2 w-100" type="submit">Bayar</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productTable = document.getElementById('product-table').getElementsByTagName('tbody')[0];
            let itemCount = 1;
            let timer;

            document.getElementById('search-input').addEventListener('input', function() {
                clearTimeout(timer); // Clear the previous timer to prevent making unnecessary requests
                setTimeout(() => {
                    let query = this.value;

                    if (query.length > 0) {
                        let url =
                            `http://127.0.0.1:8001/api/items?search=${encodeURIComponent(query)}`;

                        fetch(url)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                let searchResults = document.getElementById('search-results');
                                searchResults.innerHTML = ''; // Clear previous results

                                if (data.length > 0) {
                                    data.forEach(item => {
                                        let resultElement = document.createElement('a');
                                        resultElement.className = 'dropdown-item';
                                        resultElement.href = '#';
                                        resultElement.textContent =
                                            `${item.name} - ${item.price.toLocaleString('id-ID')}`;
                                        resultElement.dataset.price = item.price;
                                        resultElement.dataset.name = item.name;
                                        resultElement.dataset.productId = item
                                            .id; // Add product ID to dataset

                                        resultElement.addEventListener('click',
                                            function(e) {
                                                e.preventDefault();
                                                addItemToTable(item);
                                            });

                                        searchResults.appendChild(resultElement);
                                    });
                                } else {
                                    searchResults.innerHTML =
                                        '<span class="dropdown-item text-warning">No results found</span>';
                                }
                            })
                            .catch(error => {
                                console.error("Error fetching data:", error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: `Error fetching results: ${error.message}`
                                });
                            });
                    } else {
                        document.getElementById('search-results').innerHTML =
                            ''; // Clear results if query is empty
                    }
                }, 1000);
            });

            function addItemToTable(item) {
                // Check if item is already in the table
                let existingRow = Array.from(productTable.rows).find(row => {
                    return row.querySelector('td:nth-child(2)').textContent.trim() === item.name;
                });

                if (existingRow) {
                    // If item is already in the table, update quantity
                    let quantityInput = existingRow.querySelector('.quantity');
                    let quantity = parseInt(quantityInput.value);
                    if (isNaN(quantity)) {
                        quantity = 0;
                    }
                    quantityInput.value = quantity + 1;
                    quantityInput.dispatchEvent(new Event('input')); // Trigger input event to update subtotal
                } else {
                    // Add new row to the table
                    let row = productTable.insertRow();
                    row.innerHTML = `
                        <td>${itemCount++}</td>
                        <td>${item.name}</td>
                        <td>Rp. ${item.price.toLocaleString('id-ID')}</td>
                        <td><input type="number" class="form-control quantity" value="1" data-price="${item.price}" data-product-id="${item.id}"></td>
                        <td class="subtotal">${item.price.toLocaleString('id-ID')}</td>
                        <td><button class="btn btn-danger">Hapus</button></td>
                    `;

                    // Attach event listener to the quantity input of the new row
                    row.querySelector('.quantity').addEventListener('input', function() {
                        updateSubtotalAndTotal();
                    });

                    // Add remove functionality
                    row.querySelector('.btn-danger').addEventListener('click', function() {
                        row.remove();
                        updateSubtotalAndTotal();
                    });

                    updateSubtotalAndTotal();
                }
            }

            function updateSubtotalAndTotal() {
                let totalCost = 0;

                // Update subtotal and calculate total cost
                productTable.querySelectorAll('tr').forEach(row => {
                    let quantityInput = row.querySelector('.quantity');
                    let price = parseFloat(quantityInput.getAttribute('data-price'));
                    let quantity = parseInt(quantityInput.value) || 0;
                    let subtotal = price * quantity;
                    row.querySelector('.subtotal').textContent = `${subtotal.toLocaleString('id-ID')}`;
                    totalCost += subtotal;
                });

                // Display the total cost
                document.getElementById('total-cost').textContent = `${totalCost.toLocaleString('id-ID')}`;
                updateChange();
            }

            // Listen for changes in the "Bayar" input
            document.getElementById('amount-paid').addEventListener('input', updateChange);

            function updateChange() {
                let totalCost = parseFloat(document.getElementById('total-cost').textContent.replace(/[^\d]/g, ''));
                let amountPaid = parseFloat(document.getElementById('amount-paid').value) || 0;
                let change = amountPaid - totalCost;

                document.getElementById('change').textContent = `${change.toLocaleString('id-ID')}`;
            }
        });

        document.querySelector('.btn-success').addEventListener('click', function() {
            submitTransaction();
        });

        // Function to get a cookie by name
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        function submitTransaction() {
            const totalItems = Array.from(document.querySelectorAll('.quantity')).reduce((sum, input) => {
                return sum + parseInt(input.value || 0);
            }, 0);

            const totalCost = parseFloat(document.getElementById('total-cost').textContent.replace(/[^\d]/g, ''));

            const amountPaid = parseFloat(document.getElementById('amount-paid').value) || 0;
            const change = amountPaid - totalCost;

            // Ambil invoice number dari elemen HTML yang sudah ditampilkan di halaman
            const invoiceCode = document.getElementById('invoice-number').textContent;

            const details = Array.from(document.querySelectorAll('#product-table tbody tr')).map(row => ({
                product_name: row.querySelector('td:nth-child(2)').textContent,
                product_price: parseFloat(row.querySelector('td:nth-child(3)').textContent.replace(/[^\d]/g,
                    '')),
                quantity: parseInt(row.querySelector('.quantity').value || 0),
            }));

            const transactionData = {
                invoice_code: invoiceCode,
                total_items: totalItems,
                total_price: totalCost,
                change: change, // Include change
                bayar: amountPaid, // Include bayar
                details: details,
            };

            fetch('http://127.0.0.1:8001/api/transaksi/store', {
                    method: 'POST',
                    credentials: 'include', // Important for including cookies
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'), // CSRF token
                    },
                    body: JSON.stringify(transactionData),
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errData => {
                            throw new Error(errData.message || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Transaction successfully saved!',
                    });
                    // Reset or clear the form as needed
                    printSummary();
                    resetForm();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Save Transaction',
                        text: error.message,
                    });
                });
        }

        function generateInvoiceNumber() {
            // Check if an invoice number is already in localStorage
            if (!localStorage.getItem('invoiceNumber')) {
                // Generate a random invoice number and store it in localStorage
                const invoiceNumber = 'INV-' + Math.random().toString(36).substring(2, 10).toUpperCase();
                localStorage.setItem('invoiceNumber', invoiceNumber);
            }

            // Update the invoice number in the UI
            document.getElementById('invoice-number').textContent = localStorage.getItem('invoiceNumber');
        }

        function resetForm() {
            // Clear the form fields
            document.getElementById('search-input').value = '';
            document.getElementById('search-results').innerHTML = '';
            document.getElementById('product-table').getElementsByTagName('tbody')[0].innerHTML = '';
            document.getElementById('total-cost').textContent = '0';
            document.getElementById('amount-paid').value = '';
            document.getElementById('change').textContent = '0';

            // Generate a new invoice number by clearing the current one and creating a new one
            localStorage.removeItem('invoiceNumber');
            generateInvoiceNumber(); // This will create a new invoice number
        }

        generateInvoiceNumber();

        function printSummary() {
            // Get the recipe data from the table
            const productTable = document.getElementById('product-table').getElementsByTagName('tbody')[0];
            let totalCost = 0;

            // Create a new window for printing
            const printWindow = window.open('', '', 'height=600,width=800');

            // Add content to the new window
            printWindow.document.write('<html><head><title>Arka Cashier</title>');
            printWindow.document.write(
                '<style>body { font-family: Arial, sans-serif; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f4f4f4; text-align: left; }</style>'
                );
            printWindow.document.write('</head><body >');
            printWindow.document.write('<h1>Transaction Summary</h1>');

            // Add the product table content
            printWindow.document.write('<h2>Items</h2>');
            printWindow.document.write('<table>');
            printWindow.document.write(
                '<thead><tr><th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr></thead>');
            printWindow.document.write('<tbody>');

            // Calculate subtotal for each item and total cost
            Array.from(productTable.rows).forEach(row => {
                const itemName = row.querySelector('td:nth-child(2)').textContent;
                const price = parseFloat(row.querySelector('td:nth-child(3)').textContent.replace(/[^\d]/g, ''));
                const quantity = parseInt(row.querySelector('.quantity').value) || 0;
                const subtotal = price * quantity;
                totalCost += subtotal;

                printWindow.document.write(
                    `<tr><td>${itemName}</td><td>Rp. ${price.toLocaleString('id-ID')}</td><td>${quantity}</td><td>Rp. ${subtotal.toLocaleString('id-ID')}</td></tr>`
                    );
            });

            printWindow.document.write('</tbody></table>');

            // Get total bayar and change
            const totalBayar = parseFloat(document.getElementById('total-cost').textContent.replace(/[^\d]/g, ''));
            const amountPaid = parseFloat(document.getElementById('amount-paid').value) || 0;
            const change = amountPaid - totalBayar;

            // Print the summary of totals
            printWindow.document.write('<h2>Summary</h2>');
            printWindow.document.write(`<p><strong>Total Subcost:</strong> Rp. ${totalBayar.toLocaleString('id-ID')}</p>`);
            printWindow.document.write(`<p><strong>Total Bayar:</strong> Rp. ${amountPaid.toLocaleString('id-ID')}</p>`);
            printWindow.document.write(`<p><strong>Kembalian:</strong> Rp. ${change.toLocaleString('id-ID')}</p>`);

            printWindow.document.write('</body></html>');

            // Close the document to finish writing
            printWindow.document.close();

            // Wait for the content to be fully loaded before printing
            printWindow.onload = function() {
                printWindow.focus(); // Focus on the new window
                printWindow.print(); // Print the content
            };
        }
    </script>


</body>

</html>
