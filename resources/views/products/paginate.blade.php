<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Codigo de barras</th>
            <th scope="col">Nombre</th>
            <th scope="col">Stock</th>
            <th scope="col">Costo</th>
            <th scope="col">Precio 1</th>
            <th scope="col">Precio 2</th>
            <th scope="col">Precio 3</th>
            <th scope="col">IVA</th>
            <th scope="col">Categoria</th>
            <th scope="col">Marca</th>
            @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                <th scope="col">Sucursal</th>
            @endif
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productos as $item)
            <tr>
                <th scope="row">{{$item->bar_code}}</th>
                <td>{{$item->name}}</td>
                <td>{{$item->stock}}</td>
                <td>${{$item->cost}}</td>
                <td>${{$item->price_1}}</td>
                @if($item->price_2)
                    <td>${{$item->price_2}}</td>
                @else
                    <td>----</td>
                @endif
                @if($item->price_3)
                    <td>${{$item->price_3}}</td>
                @else
                    <td>---</td>
                @endif

                @if($item->iva == null)
                    <td>-</td>
                @else
                    <td>{{$item->iva}}</td>
                @endif
                <td>{{$item->category->name}}</td>
                <td>{{$item->brand->name ?? '-'}}</td>
                @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                    @if($item->branch_office == null)
                        <td>-</td>
                    @else
                        <td>{{$item->branch_office->name}}</td>
                    @endif
                @endif
                <td>
                    <button onclick="llenar({{$item}})" type="button" class="btn btn-outline-secondary btn-sm my-2" data-type="edit" data-toggle="modal" data-target="#productModalEdit">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                        </svg>
                        </button>
                    <form onsubmit="return confirm('Eliminar producto?')" action="/product/{{$item->id}}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-outline-danger btn-sm my-2" data-type="delete">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                            </svg>
                        </button>
                    </form>
                    <a href="{{route('tag',$item)}}" target="blank" type="button" class="btn btn-outline-primary"><i class="bi bi-upc"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{!! $productos->links() !!}

