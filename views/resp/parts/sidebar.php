<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
    <div class="container-fluid d-flex flex-column p-0"><a
                class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
            <div class="sidebar-brand-icon rotate-n-15"><i class="far fa-handshake"></i></div>
            <div class="sidebar-brand-text mx-3"><span>Gestion<br>De Stage</span></div>
        </a>
        <hr class="sidebar-divider my-0">
        <ul class="navbar-nav text-light" id="accordionSidebar">
            <li class="nav-item">
                <a class="nav-link" href="/">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/etudiants">
                    <i class="fa fa-gift"></i>
                    <span>Etudiants</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/offres">
                    <i class="fa fa-gift"></i>
                    <span>Offres de stage</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/entreprises">
                    <i class="fa fa-gift"></i>
                    <span>Entreprises</span></a>
            </li>
        </ul>
        <div class="text-center d-none d-md-inline">
            <button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button>
        </div>
    </div>
</nav>

<script>
    const path = "/" + location.pathname.substr(1);
    const navlink = document.querySelector(`a.nav-link[href="${path}"]`)
    if (navlink != null)
        navlink.classList.add('active');
</script>