<!-- sidebar_superadmin.php -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 260px; height: 100vh; background: linear-gradient(180deg, #212529, #343a40); position: fixed;">
    <a href="dashboard_superadmin.php" class="d-flex align-items-center mb-4 text-white text-decoration-none">
        <i class="bi bi-shield-lock-fill fs-4 me-2 text-warning"></i>
        <span class="fs-5 fw-semibold">Superadmin Panel</span>
    </a>
    <hr class="border-secondary">

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="home_superadmin.php" class="nav-link text-white d-flex align-items-center">
                <i class="bi bi-house-door-fill me-3 text-primary"></i>
                <span>Home</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="superadmin_dashboard.php" class="nav-link text-white d-flex align-items-center">
                <i class="bi bi-people-fill me-3 text-info"></i>
                <span>Kelola Pegawai</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="kelola_mitra.php" class="nav-link text-white d-flex align-items-center">
                <i class="bi bi-person-lines-fill me-3 text-success"></i>
                <span>Kelola Mitra</span>
            </a>
        </li>
    </ul>

    <hr class="border-secondary">

    <div class="mt-auto text-center">
        <a href="superadmin.php" class="btn btn-outline-light w-100 mt-2">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </a>
    </div>
</div>


<style>
    .nav-link {
        transition: background 0.2s, padding-left 0.2s;
        border-radius: 8px;
        padding: 10px;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        padding-left: 18px;
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
    }
</style>