<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header mb-3">
            <a href="/home" class="b-brand text-primary">
                <div class="logo-container">
                    <img src="assets/img/logo.png" alt="logo">
                </div>
            </a>
        </div>

        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Dashboard</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <li class="pc-item">
                    <a href="/home" class="pc-link"><span class="pc-micon"><i class="ti ti-dashboard"></i></span><span
                            class="pc-mtext">Dashboard</span></a>
                </li>
                @if (auth()->user()->role_id == 1)
                    <li class="pc-item pc-caption">
                        <label>Core Function</label>
                        <i class="ti ti-apps"></i>
                    </li>

                    <li class="pc-item">
                        <a href="/add-user" class="pc-link"><span class="pc-micon"><i
                                    class="ti ti-user-plus"></i></span><span class="pc-mtext">Add User</span></a>
                    </li>

                    <li class="pc-item">
                        <a href="/user-list" class="pc-link"><span class="pc-micon"><i
                                    class="ti ti-list"></i></span><span class="pc-mtext">User List</span></a>
                    </li>
                    <li class="pc-item">
                        <a href="/add-image" class="pc-link"><span class="pc-micon"><i
                                    class="ti ti-cloud-upload"></i></span><span class="pc-mtext">Upload
                                Image's</span></a>
                    </li>
                    <li class="pc-item">
                        <a href="/upload-catagoties" class="pc-link"><span class="pc-micon"><i
                                    class="ti ti-file-export"></i></span><span class="pc-mtext">Upload
                                Catagoties</span></a>
                    </li>
                    <li class="pc-item">
                        <a href="/coupons" class="pc-link"><span class="pc-micon"><i
                                    class="ti ti-discount-2"></i></span><span class="pc-mtext">Apply Discount</span></a>
                    </li>
                @endif
            </ul>

        </div>
    </div>
</nav>

<style>
    .logo-container img {
        width: 100px;
        /* Adjust size */
        height: auto;
        max-width: 100%;
        object-fit: contain;
        /* Ensures proper scaling */
    }
</style>
