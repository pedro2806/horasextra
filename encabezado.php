<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-2 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Logo a la izquierda -->
    <img class="sidebar-card-illustration" src="img/MESS_05_Imagotipo_1.jpg" height="50">

    <!-- Navbar derecha -->
    <ul class="navbar-nav ml-auto">

        <!-- Toggle tema claro/oscuro -->
        <li class="nav-item d-flex align-items-center mr-2">
            <button id="themeToggle" type="button" class="theme-toggle-btn" title="Cambiar tema">
                <i class="fas fa-moon"></i>
            </button>
        </li>
        <script>
        (function () {
            var btn = document.getElementById('themeToggle');
            if (!btn) return;
            function applyTheme(theme) {
                var icon = btn.querySelector('i');
                if (theme === 'dark') {
                    document.body.classList.add('theme-dark');
                    if (icon) { icon.classList.remove('fa-moon'); icon.classList.add('fa-sun'); }
                } else {
                    document.body.classList.remove('theme-dark');
                    if (icon) { icon.classList.remove('fa-sun'); icon.classList.add('fa-moon'); }
                }
                try { localStorage.setItem('mess-theme', theme); } catch (e) {}
            }
            applyTheme(document.body.classList.contains('theme-dark') ? 'dark' : 'light');
            btn.addEventListener('click', function () {
                applyTheme(document.body.classList.contains('theme-dark') ? 'light' : 'dark');
            });
        })();
        </script>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Usuario + foto -->
        <li class="nav-item d-flex align-items-center">
            <span class="mr-2 d-none d-sm-inline text-gray-600"><?php echo $_COOKIE['nombre'] ?? ''; ?></span>
            <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
        </li>
    </ul>
</nav>
<!-- End of Topbar -->
