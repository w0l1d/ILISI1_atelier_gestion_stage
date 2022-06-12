
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link href="https://fonts.googleapis.com/css?family=Play" rel="stylesheet">

<style>
.active {
    background: rgb(132,139,200,0.18);
    border-radius: 0.4rem;
    
  }
.pulse { transition: all .2s ease-in-out; }
.pulse:hover { transform: scale(1.15); 
                margin-left:3px;}
/***** Pulse *****/
/*
.pulse a:hover{ 
    animation: pulse 0.4s  ;
     animation-timing-function: linear; 
       
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.1);
  100% { transform: scale(1); }
  }
}*/
</style>

<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: rgb(249, 249, 249);">
            <div class="container-fluid d-flex flex-column p-0">
                <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="/">
                    <div class="sidebar-brand-icon rotate-n-15"><span style="color: rgb(115, 128, 236);">Stage</span></div>
                    <div class="sidebar-brand-text mx-3"><span style="color: rgb(0,0,0);">FSTM</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item  pulse" >
                        <a class="nav-link"  href="/">
                            <i  class="fa fa-dashboard" style="color: rgb(125, 141, 161);"></i>
                            <span style="color: rgb(115, 128, 236);">Tableau De Board</span>
                        </a>
                    </li>
                    <li class="nav-item pulse"  >
                        <a class="nav-link" href="/entreprises">
                        <i  class="fas fa-school" style="color: rgb(125, 141, 161);"></i>
                            <span style="color: rgb(115, 128, 236);">Entreprises</span>
                        </a>
                    </li>
                  
                    
                    <li class="nav-item pulse" >
                        <a class="nav-link" href="/offres">
                            <i  class="fa fa-bullhorn" style="color: rgb(125, 141, 161);"></i>
                            <span style="color: rgb(115, 128, 236);">Offre de stage</span>
                        </a>
                    </li>
                    <li class="nav-item pulse" >
                        <a class="nav-link" href="/candidature">
                            <i  class="fas fa-bookmark" style="color: rgb(125, 141, 161);"></i>
                            <span style="color: rgb(115, 128, 236);">candidature</span>
                        </a>
                    </li>
                    
                    <li class="nav-item pulse" >
                        <a class="nav-link"  href="/stages">
                            <i class="far fa-file-archive" style="color: rgb(125, 141, 161);"></i>
                            <span style="color: rgb(115, 128, 236);">Historique de Stage</span>
                        </a>
                    </li>

                  
                   
                    <li class="nav-item"></li>
                    <li class="nav-item"></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button" style="border-color: rgb(115,128,236);background: rgb(115,128,236);"></button></div>
            </div>
        </nav>

<script>
    const path = "/" + location.pathname.substr(1);
    const navlink = document.querySelector(`a.nav-link[href="${path}"]`)
    if (navlink != null)
         navlink.classList.add('active');
</script>
 





















