<nav x-data="{ open: false }" class="navbar-modern">
    <!-- Primary Navigation Menu -->
    <div class="navbar-container">
        <div class="navbar-content">
            <div class="navbar-left">
                <!-- Logo -->
                <div class="navbar-logo">
                    <a href="{{ route('dashboard') }}" class="logo-link">
                        <img src="{{ asset('Img/image.png') }}" alt="NigerDev Logo" class="logo-image">
                        <span class="logo-text">NigerDev</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="navbar-links">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Tableau de bord</span>
                    </a>
                    
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.interns.index') }}" class="nav-link {{ request()->routeIs('admin.interns.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Gestion des stagiaires</span>
                        </a>
                    @endif
                    
                    <a href="{{ route('salaire.index') }}" class="nav-link {{ request()->routeIs('salaire.*') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Paiement des salaires</span>
                    </a>
                    
                    <a href="{{ route('autorisation.index') }}" class="nav-link {{ request()->routeIs('autorisation.*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Demandes d'autorisation</span>
                    </a>
                </div>
            </div>

            <!-- User Menu -->
            <div class="navbar-right">
                <div class="user-menu" x-data="{ userOpen: false }">
                    <button @click="userOpen = !userOpen" class="user-button">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->full_name }}</span>
                            <span class="user-role">{{ Auth::user()->role === 'admin' ? 'Administrateur' : 'Stagiaire' }}</span>
                        </div>
                        <i class="fas fa-chevron-down user-chevron" :class="{ 'rotate-180': userOpen }"></i>
                    </button>

                    <div x-show="userOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.away="userOpen = false"
                         class="user-dropdown">
                        <div class="dropdown-header">
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-name">{{ Auth::user()->full_name }}</div>
                                <div class="dropdown-user-email">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-button">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Se déconnecter</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Hamburger -->
            <div class="mobile-hamburger">
                <button @click="open = !open" class="hamburger-button">
                    <span class="hamburger-line" :class="{ 'rotate-45 translate-y-2': open }"></span>
                    <span class="hamburger-line" :class="{ 'opacity-0': open }"></span>
                    <span class="hamburger-line" :class="{ '-rotate-45 -translate-y-2': open }"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="mobile-menu">
        <div class="mobile-menu-content">
            <!-- Mobile Navigation Links -->
            <div class="mobile-nav-links">
                <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a>
                
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.interns.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.interns.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Gestion des stagiaires</span>
                    </a>
                @endif
                
                <a href="{{ route('salaire.index') }}" class="mobile-nav-link {{ request()->routeIs('salaire.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Paiement des salaires</span>
                </a>
                
                <a href="{{ route('autorisation.index') }}" class="mobile-nav-link {{ request()->routeIs('autorisation.*') ? 'active' : '' }}">
                    <i class="fas fa-file-signature"></i>
                    <span>Demandes d'autorisation</span>
                </a>
            </div>

            <!-- Mobile User Section -->
            <div class="mobile-user-section">
                <div class="mobile-user-info">
                    <div class="mobile-user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="mobile-user-details">
                        <div class="mobile-user-name">{{ Auth::user()->full_name }}</div>
                        <div class="mobile-user-email">{{ Auth::user()->email }}</div>
                        <div class="mobile-user-role">{{ Auth::user()->role === 'admin' ? 'Administrateur' : 'Stagiaire' }}</div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="mobile-logout-form">
                    @csrf
                    <button type="submit" class="mobile-logout-button">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Se déconnecter</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
