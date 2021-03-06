@extends('layouts.app')

@section('content')

<div class="container">
  
@if(Auth::user()->rol_id != 1)
<h3>Historial de cortes</h3>
<br>

@endif
<div class="col-md-8">
    <div class="input-group">
        <input type="text" id="search" style="text-transform: uppercase" class="form-control" name="search" autocomplate="search" placeholder="Buscar en el historial (nombre, fecha: YYYY-M-D): ejemplo 2022-05-14"/>
        <div class="input-group-append">
            <button id="searchButton" class="btn btn-outline-secondary">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                    <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                </svg>
            </button>
        </div>
    </div>
</div>
<br>
    <table class="table">
        <thead>
            <tr>
                <th>Sucursal</th>
                <th>Usuario</th>
                <th>#Caja</th>
                <th>Fecha</th>
                <th>Reimprimir corte</th>
            </tr>
        </thead>
        <tbody id="tableResult">
            @forelse($cashClosings as $history)
            <tr>
               
                <td>{{$history->branchOffice->name}}</td>
                <td>{{$history->user->name}}</td>
                <td>{{$history->box_id}}</td>
                <td>{{$history->created_at->isoFormat('llll')}}</td>
                <td> 
                    <a href="{{route('closeBoxPdf',$history->id)}}" class="btn btn-outline-danger" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                        <path d="M5.523 12.424c.14-.082.293-.162.459-.238a7.878 7.878 0 0 1-.45.606c-.28.337-.498.516-.635.572a.266.266 0 0 1-.035.012.282.282 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548zm2.455-1.647c-.119.025-.237.05-.356.078a21.148 21.148 0 0 0 .5-1.05 12.045 12.045 0 0 0 .51.858c-.217.032-.436.07-.654.114zm2.525.939a3.881 3.881 0 0 1-.435-.41c.228.005.434.022.612.054.317.057.466.147.518.209a.095.095 0 0 1 .026.064.436.436 0 0 1-.06.2.307.307 0 0 1-.094.124.107.107 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256zM8.278 6.97c-.04.244-.108.524-.2.829a4.86 4.86 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.517.517 0 0 1 .145-.04c.013.03.028.092.032.198.005.122-.007.277-.038.465z"/>
                        <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.651 11.651 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.856.856 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.844.844 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.76 5.76 0 0 0-1.335-.05 10.954 10.954 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.238 1.238 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a19.697 19.697 0 0 1-1.062 2.227 7.662 7.662 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103z"/>
                      </svg></a>
                </td>
            </tr>
            @empty
            <td colspan="5">Sin resultados</td>
            @endforelse
        </tbody>
    </table>
    <div class="pagination" id="pag">
    {{ $cashClosings->links() }}
</div>
</div>
<script>
    let search = '';
    let option = '';
    let url = '';
    let idpag = document.getElementById('pag');
    $("#search").on("keyup", function () {
        search = $(this).val();
        url = `search/cashclosing?search=${search}`;
        idpag ? idpag.remove() : '';
        idpag = false;
       
       peticiones(url);
       
    });
    $(document).on("click", '.pagination a', function (e) {
    
    if(!idpag){
        e.preventDefault();
        page = $(this).attr("href");
        url = page;
        peticiones(url);
    }
   
 });
    const peticiones = (url) =>
    {
        fetch(url, { method: 'GET', headers: { 'X-CSRF-Token': $('meta[name="_token"]').attr('content') } })
        .then(response => response.text())
        .then(html => {
            document.getElementById('tableResult').innerHTML = html;
           
        });
    }
   

</script>
@endsection