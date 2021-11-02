<html>
    <head>
        <style type="text/css">
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
                text-align: center;
                border-color: #424242;
            }
        </style>
    </head> 
    <body>
        <div style="text-align:center; margin-left: auto; margin-right: auto;">
            <h3>NOMBRE DEL NEGOCIO</h3>
            <h4>REPORTE DE VENTAS</h4>

            <!-- Para reportes sucursales -->
            <table style="width: 100%;">
                <tr>
                    <th>SUCURSAL</th>
                    <th>GERENTE</th>
                    <th>FECHA INICIO</th>
                    <th>FECHA FIN</th>
                </tr>
                <tr>
                    <td>Las Palmas</td>
                    <td>Jaime Maussan</td>
                    <td>12-12-12</td>
                    <td>12-12-12</td>
                </tr>
            </table>

            <!-- Para reportes generales -->
            <table style="width: 100%;">
                <tr>
                    <th>FECHA INICIO</th>
                    <th>FECHA FIN</th>
                </tr>
                <tr>
                    <td>12-12-12</td>
                    <td>12-12-12</td>
                </tr>
            </table>

            <!-- Para reportes generales -->
            <table style="width: 100%; margin-top:20px;">
                <tr>
                    <th>SUCURSAL</th>
                    <th>EMPLEADO</th>
                </tr>
                <tr>
                    <td>LAS PALMAS</td>
                    <td>José Díaz</td>
                </tr>
            </table>

            <!-- Para de personal por sucursal -->
            <table style="width: 100%; margin-top:20px;">
                <tr>
                    <th>EMPLEADO</th>
                </tr>
                <tr>
                    <td>José Díaz</td>
                </tr>
            </table>

            <table style="width: 100%; margin-top:20px;">
                <tr>
                    <th>PRODUCTO</th>
                    <th>CATEGORÍA</th>
                    <th>MARCA</th>
                    <th>CANTIDAD</th>
                    <th>PRECIO <br/> UNITARIO</th>
                    <th>PRECIO <br/> PÚBLICO</th>
                    <th>DESCUENTO</th>
                    <th>TOTAL</th>
                </tr>
                <tr>
                    <td>Coca Cola 200ml</td>
                    <td>Bebidas</td>
                    <td>Coca Cola</td>
                    <td>50</td>
                    <td>$ 10.00</td>
                    <td>$ 12.00</td>
                    <td>$ 0.00</td>
                    <td>$ 600.00</td>
                </tr>
            </table>

            <table style="width: 100%; margin-top:20px;">
                <tr>
                    <th>TOTAL VENTAS</th>
                    <td>$ 600.00</td>
                    <th>INVERSIÓN</th>
                    <td>$ 500.00</td>
                </tr>
                <tr>
                    <th>GANANCIA</th>
                    <td>$ 200.00</td>
                    <th>DESCUENTOS</th>
                    <td>$ 0.00</td>
                </tr>
                <tr>
                    <th>GANANCIA REAL</th>
                    <td colspan="3">$ 600.00</td>
                </tr>
                <tr></tr>
                <tr>
                    <th>DINERO EFECTIVO</th>
                    <td>$ 200.00</td>
                    <th>DINERO ELECTRÓNICO</th>
                    <td>$ 200.00</td>
                </tr>
            </table>

            <h5 style="margin: 20px;">REPORTE GENERADO POR Nombre del admin</h5>
            <h5 style="margin: 5px;">12-12-12</h5>
            
        </div>
    </body>
</html>