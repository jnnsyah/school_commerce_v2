<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <button class="btn btn-primary" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user me-1"></i>
                    {{ auth()->user()->name }}
                    <small class="text-muted">({{ auth()->user()->getRoleName() }})</small>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#">
                        <i class="fas fa-user me-2"></i>Profile
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
    // Sidebar Toggle
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('wrapper').classList.toggle('toggled');
    });
</script>

<style>
    #wrapper {
        overflow-x: hidden;
    }
    
    #sidebar-wrapper {
        min-height: 100vh;
        margin-left: -15rem;
        transition: margin 0.25s ease-out;
    }
    
    #sidebar-wrapper .sidebar-heading {
        padding: 0.875rem 1.25rem;
        font-size: 1.2rem;
    }
    
    #sidebar-wrapper .list-group {
        width: 15rem;
    }
    
    #page-content-wrapper {
        min-width: 100vw;
    }
    
    #wrapper.toggled #sidebar-wrapper {
        margin-left: 0;
    }
    
    @media (min-width: 768px) {
        #sidebar-wrapper {
            margin-left: 0;
        }
        
        #page-content-wrapper {
            min-width: 0;
            width: 100%;
        }
        
        #wrapper.toggled #sidebar-wrapper {
            margin-left: -15rem;
        }
    }
</style>