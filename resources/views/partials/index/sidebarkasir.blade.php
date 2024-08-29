<aside class="sidebar" style="width: 250px; height: 100vh; position: fixed;">
    <h2 style="padding: 15px;">Kasir</h2>
    <div class="border-bottom border-secondary text-center" style="border-bottom: 1px solid;">
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
