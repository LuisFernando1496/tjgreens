<table class="display table table-striped table-bordered" id="example" style="width:100%;">
    <thead class="black white-text">
        <tr>
            <th scope="col">Sucursal Destino</th>
            <th scope="col">Usuario</th>
            <th scope="col">Fecha</th>
            <th scope="col">Detalles</th>
            <th scope="col">Estatus</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody id="mydata">
        @foreach ($transfers as $transfer)
        <tr>
            <th scope="row">{{$transfer->branchOffice->name}}</th>
            <td>{{$transfer->user->name}} {{$transfer->user->last_name}}</td>
            <td>{{$transfer->created_at}}</td>
            <td>{{$transfer->details}}</td>
            @if($transfer->status == 'Enviado')
            <th style="background-color: #AEEA00; color: white;">{{$transfer->status}}</th>
            @else
            <th style="background-color: #00C853; color: white;">{{$transfer->status}}</th>
            @endif
            <td>
                <a href="{{asset('transfers/'.$transfer->id)}}" class="btn btn-outline-info"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                        <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z" />
                    </svg> Ver más
                </a>
                <form action="/sendReprint" method="post" class="d-inline">
                    @csrf
                    <input type="hidden" name="transfer_id" value="{{$transfer->id}}">
                    <button type="submit" class="btn btn-outline-secondary btn-sm mx-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"/>
                          </svg> Descargar 
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>