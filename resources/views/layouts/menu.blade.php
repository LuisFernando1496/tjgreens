<div class="bg-light border-right vh-100" id="sidebar-wrapper">
    <div class="sidebar-heading" style="background-color: white;"><img class="responsive-img" src="{{asset('/logo_inusual.png')}}" /></div>

    <ul class="list-group list-group-flush overflow-auto">
        @auth
            <!-- <a href="#" class="list-group-item list-group-item-action {{ strpos(Request::url(),'home') ? 'active' : '' }}">Inicio</a> -->
            <a href="/caja" class="list-group-item list-group-item-action {{ strpos(Request::url(),'caja') ? 'active' : '' }}">Vender</a>
            <a href="/sale" class="list-group-item list-group-item-action {{ strpos(Request::url(),'sale') ? 'active' : '' }}">Ventas</a>
            @if(Auth::user()->rol_id === 1)
                <a href="/initialCash" class="list-group-item list-group-item-action {{ strpos(Request::url(),'initialCash') ? 'active' : '' }}">Establecer monto Inicial</a>
                <a href="/users" class="list-group-item list-group-item-action {{ strpos(Request::url(),'users') ? 'active' : '' }}">Usuarios</a>
                <a href="/clients" class="list-group-item list-group-item-action {{ strpos(Request::url(),'clients') ? 'active' : '' }}">Clientes</a>
                <a href="/product" class="list-group-item list-group-item-action {{ strpos(Request::url(),'product') ? 'active' : '' }}">Productos</a>
                <a href="/expense" class="list-group-item list-group-item-action {{ strpos(Request::url(),'expense') ? 'active' : '' }}">Gastos</a>
                <a href="/BranchOffice" class="list-group-item list-group-item-action {{ strpos(Request::url(),'BranchOffice') ? 'active' : '' }}">Sucursales</a>
                <a href="/box" class="list-group-item list-group-item-action {{ strpos(Request::url(),'box') ? 'active' : '' }}">Lista de cajas</a>
                <a href="/categorias" class="list-group-item list-group-item-action {{ strpos(Request::url(),'categorias') ? 'active' : '' }}">Categorias</a>
                <a href="/marcas" class="list-group-item list-group-item-action {{ strpos(Request::url(),'marcas') ? 'active' : '' }}">Marcas</a>
                <a href="/provider" class="list-group-item list-group-item-action {{ strpos(Request::url(),'provider') ? 'active' : '' }}">Proveedores</a>
                <a href="/reportes" class="list-group-item list-group-item-action {{ strpos(Request::url(),'reportes') ? 'active' : '' }}">Reportes</a>
                <a href="/purchase" class="list-group-item list-group-item-action {{ strpos(Request::url(),'purchase') ? 'active' : '' }}">Compras</a>
                <a href="/purchase-history" class="list-group-item list-group-item-action {{ strpos(Request::url(),'purchase-history') ? 'active' : '' }}">Historial de compras</a>
                <a href="/showCanceled" class="list-group-item list-group-item-action {{ strpos(Request::url(),'showCanceled') ? 'active' : '' }}">Devoluciones</a>
                <a href="/credits" class="list-group-item list-group-item-action {{ strpos(Request::url(),'credits') ? 'active' : '' }}">Creditos</a>
                <a href="/transfers" class="list-group-item list-group-item-action {{ strpos(Request::url(),'transfers') ? 'active' : '' }}">Traspasos</a>
            @elseif(Auth::user()->rol_id === 3)
                <a href="/box" class="list-group-item list-group-item-action {{ strpos(Request::url(),'box') ? 'active' : '' }}">Lista de cajas</a>
                <a href="/stock" class="list-group-item list-group-item-action {{ strpos(Request::url(),'stock') ? 'active' : '' }}">Stock</a>
                <a href="" class="list-group-item list-group-item-action {{ strpos(Request::url(),'transfers') ? 'active' : '' }}">Reportes</a>
            @endif
        @endauth
    </ul>
</div>
