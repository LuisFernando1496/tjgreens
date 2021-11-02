@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
@if($errors->any())
        @foreach($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{$error}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endforeach
    @endif
<section>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo servicio o producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <label for="fee">{{'Cantidad'}}</label>
            <input class="form-control" type="number" name="quantity" id="quantity" value="" placeholder="Ejem. " required>
            <label for="fee">{{'Descripción'}}</label>
            <input class="form-control" type="text" name="description" id="description" value="" placeholder="Ejem. " required>
            <label for="fee">{{'Precio por pieza'}}</label>
            <input class="form-control" type="number" name="price" id="price" value="" placeholder="Ejem. " required>
         
     
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button id="bt_add" class="btn btn-success" data-dismiss="modal">Agregar</button>
                    </div>
            </div>
            
        </div>
    </div>
</div>
<div style="text-align: center">
<button type="button"  class="btn  btn-success" data-toggle="modal" data-target="#exampleModal"><small>AGREGAR GASTO</small></button>
</div>
	<br>
    <div id="content">
		
		<table  id="tabla" class="table table-bordered attrTable">
		<thead>
			<tr>
				<th class="attrName">Nº</th>
				<th class="attrName">Cantidad</th>
				<th class="attrName">Descripcion</th>
                <th class="attrName">Precio/pza</th>
                <th class="attrName">Importe</th>
                
			</tr>
		</thead>
        <tbody> 
        </tbody>
	</table>
    @push('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-to-json@1.0.0/lib/jquery.tabletojson.min.js" integrity="sha256-H8xrCe0tZFi/C2CgxkmiGksqVaxhW0PFcUKZJZo1yNU=" crossorigin="anonymous" type="application/javascript"></script>
<script type="application/javascript">
        $(document).ready(function() {
            
            $('#bt_add').click(function() {
                agregar();
            });
            $('#bt_del').click(function() {
                eliminar(id_fila_selected);
            });
            $('#cobrar').click(function() {

                var dataTable = $('#tabla').tableToJSON({
                    ignoreColumns: [0]
                });
                if(dataTable.length==0){
                     alert ('Agregue gastos');
                }else{
                    console.log(JSON.stringify(dataTable));
                var request = $.ajax({
                    url: "/expenses-create",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    contentType: "application/json; charset=iso-8859-1",
                    data: JSON.stringify(dataTable),
                    dataType: 'html',
                    success: function(data) {
                        console.log(JSON.parse(data));
                        window.location.href = '/expense';
                    },
                    error: function(request, status, error) {
                        alert ('No hay caja activa');
                    },
                   

                });

                }
                


            });

        });
        var cont = 0;
        var id_fila_selected = [];
        var cantidades = [];
        var totales = [];
        var suma = 0;
        var total_quantity = 0;
        var total_amount = 0;

        function agregar() {

            var total_amount = 0;
            var quantity = parseInt(document.getElementById("quantity").value, 10);
            var description = document.getElementById("description").value;
            var price = parseInt(document.getElementById("price").value, 10);
            var amount = quantity * price;
            totales.push(amount);
            total_quantity = total_quantity + quantity;
            total_amount = total_amount + amount;
            cont++;
            var fila = '<tr class="selected" id="fila' + cont + '" onclick="seleccionar(this.id);"><td class="attrValue">' + cont + '</td><td class="attrValue">' + quantity + '</td><td class="attrValue">' + description + '</td><td class="attrValue">' + price + '</td><td class="attrValue">' + amount + '</td></tr>';
            $('#tabla').append(fila);
            $('#total_quantity').val(total_quantity);
            reordenar();
            var suma = 0;
            totales.forEach(function(total) {
                suma += total;
            });
            $('#total_amount').val(suma);
        }



        function seleccionar(id_fila) {
            if ($('#' + id_fila).hasClass('seleccionada')) {
                $('#' + id_fila).removeClass('seleccionada');
            } else {
                $('#' + id_fila).addClass('seleccionada');
            }
            //2702id_fila_selected=id_fila;
            id_fila_selected.push(id_fila);
        }

        function eliminar(id_fila) {
            /*$('#'+id_fila).remove();
            reordenar();*/
            for (var i = 0; i < id_fila.length; i++) {
                $('#' + id_fila[i]).remove();
            }
            reordenar();
        }

        function reordenar() {
            var num = 1;
            $('#tabla tbody tr').each(function() {
                $(this).find('td').eq(0).text(num);
                num++;
            });
        }

        function eliminarTodasFilas() {
            $('#tabla tbody tr').each(function() {
                $(this).remove();
            });

        }
    </script>

@endpush('scripts')

    <div style="text-align: right">
        <button id="bt_del" class="btn btn-outline-danger btn-sm">Eliminar</button>
    </div>
    
	</div>

    <div style="text-align: center">
    <div class="modal fade" id="finishTransaction" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Realizar cobro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: center">
                ¿Está seguro de realizar el cobro?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                <div class="float-center">
                        <button  name="cobrar" id="cobrar" class="btn btn-outline-success" data-dismiss="modal">cobrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
    /* Este fue el código que pegue de la pagina*/
    $( document ).ready(function() {
    !function(a){"use strict";a.fn.tableToJSON=function(b){var c={ignoreColumns:[],onlyColumns:null,ignoreHiddenRows:!0,ignoreEmptyRows:!1,headings:null,allowHTML:!1,includeRowId:!1,textDataOverride:"data-override",textExtractor:null};b=a.extend(c,b);var d=function(a){return void 0!==a&&null!==a},e=function(c){return d(b.onlyColumns)?-1===a.inArray(c,b.onlyColumns):-1!==a.inArray(c,b.ignoreColumns)},f=function(b,c){var e={},f=0;return a.each(c,function(a,c){f<b.length&&d(c)&&(e[b[f]]=c,f++)}),e},g=function(c,d,e){var f=a(d),g=b.textExtractor,h=f.attr(b.textDataOverride);return null===g||e?a.trim(h||(b.allowHTML?f.html():d.textContent||f.text())||""):a.isFunction(g)?a.trim(h||g(c,f)):"object"==typeof g&&a.isFunction(g[c])?a.trim(h||g[c](c,f)):a.trim(h||(b.allowHTML?f.html():d.textContent||f.text())||"")},h=function(c,d){var e=[],f=b.includeRowId,h="boolean"==typeof f?f:"string"==typeof f?!0:!1,i="string"==typeof f==!0?f:"rowId";return h&&"undefined"==typeof a(c).attr("id")&&e.push(i),a(c).children("td,th").each(function(a,b){e.push(g(a,b,d))}),e},i=function(a){var c=a.find("tr:first").first();return d(b.headings)?b.headings:h(c,!0)},j=function(c,h){var i,j,k,l,m,n,o,p=[],q=0,r=[];return c.children("tbody,*").children("tr").each(function(c,e){if(c>0||d(b.headings)){var f=b.includeRowId,h="boolean"==typeof f?f:"string"==typeof f?!0:!1;n=a(e);var r=n.find("td").length===n.find("td:empty").length?!0:!1;!n.is(":visible")&&b.ignoreHiddenRows||r&&b.ignoreEmptyRows||n.data("ignore")&&"false"!==n.data("ignore")||(q=0,p[c]||(p[c]=[]),h&&(q+=1,"undefined"!=typeof n.attr("id")?p[c].push(n.attr("id")):p[c].push("")),n.children().each(function(){for(o=a(this);p[c][q];)q++;if(o.filter("[rowspan]").length)for(k=parseInt(o.attr("rowspan"),10)-1,m=g(q,o),i=1;k>=i;i++)p[c+i]||(p[c+i]=[]),p[c+i][q]=m;if(o.filter("[colspan]").length)for(k=parseInt(o.attr("colspan"),10)-1,m=g(q,o),i=1;k>=i;i++)if(o.filter("[rowspan]").length)for(l=parseInt(o.attr("rowspan"),10),j=0;l>j;j++)p[c+j][q+i]=m;else p[c][q+i]=m;m=p[c][q]||g(q,o),d(m)&&(p[c][q]=m),q++}))}}),a.each(p,function(c,g){if(d(g)){var i=d(b.onlyColumns)||b.ignoreColumns.length?a.grep(g,function(a,b){return!e(b)}):g,j=d(b.headings)?h:a.grep(h,function(a,b){return!e(b)});m=f(j,i),r[r.length]=m}}),r},k=i(this);return j(this,k)}}(jQuery);
    });
    </script>
    <button type="button" class="btn btn-success " data-toggle="modal" data-target="#finishTransaction"> <small>COBRAR</small></button>
    </div>
</section>
@stop
