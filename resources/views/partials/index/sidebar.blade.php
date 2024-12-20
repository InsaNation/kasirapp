<aside class="sidebar" style="width: 250px; height: 100vh; position: fixed;">
    <h2 style="padding: 15px;">Admin Panel</h2>
    <div>
        <a href="{{ route('dashboard_admin') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}"
            style="display: block; padding: 10px; text-decoration: none;">
            <span>Dashboard</span>
        </a>
    </div>
    <div class="border-bottom border-secondary text-center" style="border-bottom: 1px solid;">
        <a href="#dataDropdown" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#dataDropdown"
            aria-controls="dataDropdown" id="btn-collapse"
            style="display: block; padding: 10px; text-decoration: none;">
            <span>Data</span>
        </a>
        <div class="collapse{{ request()->is('admin/data') || request()->is('admin/data_items') || request()->is('admin/laporan') ? 'show' : '' }}"
            id="dataDropdown" style="padding-left: 15px;">
            <a href="{{ route('data_user') }}" class="{{ request()->is('admin/data') ? 'active' : '' }}"
                style="display: block; padding: 10px; text-decoration: none;">
                <span>Data User</span>
            </a>
            <a href="{{ route('index_item') }}" class="{{ request()->is('admin/data_items') ? 'active' : '' }}"
                style="display: block; padding: 10px; text-decoration: none;">
                <span>Data Item</span>
            </a>
            {{-- <a
                style="display: block; padding: 10px; text-decoration: none;">
                <span>Laporan Transaksi (On Progress)</span>
            </a> --}} jadi ini di aktifin atau engga

            kalo nampilin kasirnya gimana jadi

        </div>
    </div>
    <div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" id="logout-link" style="color: white; display: block; padding: 10px; text-decoration: none;">
            Logout
        </a>
    </div>
</aside>

<!-- Include Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
