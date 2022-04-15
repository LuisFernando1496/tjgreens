<!--<div style="background-color: #ff3333; text-align: center; color: azure">
<h2>Su hosting de servidor está por ser suspendido por falta de pago. Su fecha limite para realizar su pago es: 25/12/2020</h2>
</div>-->
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand">
            @if (Auth::user())
                @if (Auth::user()->rol_id != 4)
                    {{ Auth::user()->branchOffice->name }}
                @else
                    @if (Auth::user()->rol_id == 4)
                        Almacen TJGreens
                    @else
                    {{ config('app.name', 'Laravel') }}
                    @endif
                   
                @endif
                @else
                {{ config('app.name', 'Laravel') }}
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link" type="button" data-toggle="modal" data-target="#exampleModal">
                            <div>
                                <i class="fas fa-bell fa-lg mb-1"></i>
                                <span class="badge rounded-pill badge-notification bg-info">1</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="perfil">Perfil</a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                {{ __('Cerrar sesión') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
