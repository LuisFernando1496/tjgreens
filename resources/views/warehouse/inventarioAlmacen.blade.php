<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
  
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" style="text-align: center">Reporte de Inventario</h4>
            </div>
            <div class="card-body">
                <h6 class="card-title">Productos</h6>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoria</th>
                            <th>Marca</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Costo</th>
                            
                        </tr>
                    </thead>
                    <tbody id="inventarios">
                        @forelse ($productos as $count => $inventario)
                            <tr>
                                <td>{{ $count+1 }}</td>
                                <td>{{ $inventario->bar_code }}</td>
                                <td>{{ $inventario->name }}</td>
                                <td>{{ $inventario->categoria->name }}</td>
                                <td>{{ $inventario->marca->name }}</td>
                                <td>{{ $inventario->stock }}</td>
                                <td>${{ $inventario->price }}</td>
                                <td>${{ $inventario->cost }}</td>
                               
                            </tr>
                        @empty

                        @endforelse
                    </tbody>
                </table>
               
            </div>
        </div>
    </div>
    <script>
        window.print();
        window.addEventListener("afterprint", function(event) {
            window.close()
        });
    </script>
</body>
</html>
