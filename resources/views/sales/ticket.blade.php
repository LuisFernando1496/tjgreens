<!doctype html>
<html lang="es">
<!--
  Plantilla inicial de Bootstrap 4
  @author parzibyte
  Visita: parzibyte.me/blog
-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,
            shrink-to-fit=no">
    <meta name="description" content="Demostrar capacidades de plugin de impresión para impresora térmica en JavaScript">
    <meta name="author" content="Parzibyte">
    <title>Demostrar capacidades de plugin de impresión</title>
    <!-- Cargar el CSS de Boostrap-->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <main role="main" class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Demostrar capacidades de plugin de impresión</h1>
                <a href="//parzibyte.me/blog">By Parzibyte</a>
                <br>
                <a class="btn btn-danger btn-sm" href="../../index.html">Documentación</a>
            </div>
            <!-- Aquí pon las col-x necesarias, comienza tu contenido, etcétera -->
            <div class="col-12 col-lg-6">

                <h2>Ajustes de impresora</h2>
                <strong>Nombre de impresora seleccionada: </strong>
                <p id="impresoraSeleccionada"></p>
                <div class="form-group">
                    <select class="form-control" id="listaDeImpresoras"></select>
                </div>
                <button class="btn btn-primary btn-sm" id="btnRefrescarLista">Refrescar lista</button>
                <button class="btn btn-primary btn-sm" id="btnEstablecerImpresora">Establecer como predeterminada</button>
                <h2>Capacidades</h2>
                <p>Utiliza el siguiente botón para imprimir un recibo de prueba en la impresora predeterminada:</p>
                <button class="btn btn-success" id="btnImprimir">Imprimir ticket</button>

            </div>
            <div class="col-12 col-lg-6">
                <h2>Log</h2>
                <button class="btn btn-warning btn-sm" id="btnLimpiarLog">Limpiar log</button>
                <pre id="estado"></pre>
            </div>
        </div>
    </main>
    <script>
        /**
 * Una clase para interactuar con el plugin
 * 
 * @author parzibyte
 * @see https://parzibyte.me/blog
 */
const C = {
    AccionWrite: "write",
    AccionCut: "cut",
    AccionCash: "cash",
    AccionCutPartial: "cutpartial",
    AccionAlign: "align",
    AccionFontSize: "fontsize",
    AccionFont: "font",
    AccionEmphasize: "emphasize",
    AccionFeed: "feed",
    AccionQr: "qr",
    AlineacionCentro: "center",
    AlineacionDerecha: "right",
    AlineacionIzquierda: "left",
    FuenteA: "A",
    FuenteB: "B",
    AccionBarcode128: "barcode128",
    AccionBarcode39: "barcode39",
    AccionBarcode93: "barcode93",
    AccionBarcodeEAN: "barcodeEAN",
    AccionBarcodeTwoOfFiveSinInterleaved: "barcodeTwoOfFive",
    AccionBarcodeTwoOfFiveInterleaved: "barcodeTwoOfFiveInterleaved",
    AccionBarcodeCodabar: "barcodeCodabar",
    AccionBarcodeUPCA: "barcodeUPCA",
    AccionBarcodeUPCE: "barcodeUPCE",
    Medida80: 80,
    Medida100: 100,
    Medida156: 156,
    Medida200: 200,
    Medida300: 300,
    Medida350: 350,
};

const URL_PLUGIN = "http://localhost:8000";

class OperacionTicket {
    constructor(accion, datos) {
        this.accion = accion + "";
        this.datos = datos + "";
    }
}
class Impresora {
    constructor(ruta) {
        if (!ruta) ruta = URL_PLUGIN;
        this.ruta = ruta;
        this.operaciones = [];
    }

    static setImpresora(nombreImpresora, ruta) {
        if (ruta) URL_PLUGIN = ruta;
        return fetch(URL_PLUGIN + "/impresora", {
                method: "PUT",
                body: JSON.stringify(nombreImpresora),
            })
            .then(r => r.json())
            .then(respuestaDecodificada => respuestaDecodificada === nombreImpresora);
    }

    static getImpresora(ruta) {
        if (ruta) URL_PLUGIN = ruta;
        return fetch(URL_PLUGIN + "/impresora")
            .then(r => r.json());
    }

    static getImpresoras(ruta) {
        if (ruta) URL_PLUGIN = ruta;
        return fetch(URL_PLUGIN + "/impresoras")
            .then(r => r.json());
    }

    static getImpresorasRemotas(ip) {
        return fetch(URL_PLUGIN + "/impresoras_remotas?ip=" + ip)
            .then(r => r.json());
    }

    cut() {
        this.operaciones.push(new OperacionTicket(C.AccionCut, ""));
    }

    cash() {
        this.operaciones.push(new OperacionTicket(C.AccionCash, ""));
    }

    cutPartial() {
        this.operaciones.push(new OperacionTicket(C.AccionCutPartial, ""));
    }

    setFontSize(a, b) {
        this.operaciones.push(new OperacionTicket(C.AccionFontSize, `${a},${b}`));
    }

    setFont(font) {
        if (font !== C.FuenteA && font !== C.FuenteB) throw Error("Fuente inválida");
        this.operaciones.push(new OperacionTicket(C.AccionFont, font));
    }
    setEmphasize(val) {
        if (isNaN(parseInt(val)) || parseInt(val) < 0) throw Error("Valor inválido");
        this.operaciones.push(new OperacionTicket(C.AccionEmphasize, val));
    }
    setAlign(align) {
        if (align !== C.AlineacionCentro && align !== C.AlineacionDerecha && align !== C.AlineacionIzquierda) {
            throw Error(`Alineación ${align} inválida`);
        }
        this.operaciones.push(new OperacionTicket(C.AccionAlign, align));
    }

    write(text) {
        this.operaciones.push(new OperacionTicket(C.AccionWrite, text));
    }

    feed(n) {
        if (!parseInt(n) || parseInt(n) < 0) {
            throw Error("Valor para feed inválido");
        }
        this.operaciones.push(new OperacionTicket(C.AccionFeed, n));
    }

    end() {
        return fetch(this.ruta + "/imprimir", {
                method: "POST",
                body: JSON.stringify(this.operaciones),
            })
            .then(r => r.json());
    }

    imprimirEnImpresora(nombreImpresora) {
        const payload = {
            operaciones: this.operaciones,
            impresora: nombreImpresora,
        };
        return fetch(this.ruta + "/imprimir_en", {
                method: "POST",
                body: JSON.stringify(payload),
            })
            .then(r => r.json());
    }

    qr(contenido) {
        this.operaciones.push(new OperacionTicket(C.AccionQr, contenido));
    }

    validarMedida(medida) {
        medida = parseInt(medida);
        if (medida !== C.Medida80 &&
            medida !== C.Medida100 &&
            medida !== C.Medida156 &&
            medida !== C.Medida200 &&
            medida !== C.Medida300 &&
            medida !== C.Medida350) {
            throw Error("Valor para medida del barcode inválido");
        }
    }

    validarTipo(tipo) {
        if (
            [C.AccionBarcode128,
                C.AccionBarcode39,
                C.AccionBarcode93,
                C.AccionBarcodeEAN,
                C.AccionBarcodeTwoOfFiveInterleaved,
                C.AccionBarcodeTwoOfFiveSinInterleaved,
                C.AccionBarcodeCodabar,
                C.AccionBarcodeUPCA,
                C.AccionBarcodeUPCE,
            ]
            .indexOf(tipo) === -1
        ) throw Error("Tipo de código de barras no soportado");
    }

    barcode(contenido, medida, tipo) {
        this.validarMedida(medida);
        this.validarTipo(tipo);
        let payload = contenido.concat(",").concat(medida.toString());
        this.operaciones.push(new OperacionTicket(tipo, payload));
    }
    imprimirEnImpresoraConNombreEIp(nombreImpresora, ip) {
        const payload = {
            operaciones: this.operaciones,
            impresora: nombreImpresora,
            ip: ip,
        };
        return fetch(this.ruta + "/imprimir_y_reenviar", {
            method: "POST",
            body: JSON.stringify(payload),
        })
            .then(r => r.json());
    }

}
 // importante
const RUTA_API = "http://localhost:8000"
const $estado = document.querySelector("#estado"),
    $listaDeImpresoras = document.querySelector("#listaDeImpresoras"),
    $btnLimpiarLog = document.querySelector("#btnLimpiarLog"),
    $btnRefrescarLista = document.querySelector("#btnRefrescarLista"),
    $btnEstablecerImpresora = document.querySelector("#btnEstablecerImpresora"),
    $texto = document.querySelector("#texto"),
    $impresoraSeleccionada = document.querySelector("#impresoraSeleccionada"),
    $btnImprimir = document.querySelector("#btnImprimir");



const loguear = texto => $estado.textContent += (new Date()).toLocaleString() + " " + texto + "\n";
const limpiarLog = () => $estado.textContent = "";

$btnLimpiarLog.addEventListener("click", limpiarLog);

const limpiarLista = () => {
    for (let i = $listaDeImpresoras.options.length; i >= 0; i--) {
        $listaDeImpresoras.remove(i);
    }
};


const obtenerListaDeImpresoras = () => {
    loguear("Cargando lista...");
    Impresora.getImpresoras()
        .then(listaDeImpresoras => {
            refrescarNombreDeImpresoraSeleccionada();
            loguear("Lista cargada");
            limpiarLista();
            listaDeImpresoras.forEach(nombreImpresora => {
                const option = document.createElement('option');
                option.value = option.text = nombreImpresora;
                $listaDeImpresoras.appendChild(option);
            })
        });
}

const establecerImpresoraComoPredeterminada = nombreImpresora => {
    loguear("Estableciendo impresora...");
    Impresora.setImpresora(nombreImpresora)
        .then(respuesta => {
            refrescarNombreDeImpresoraSeleccionada();
            if (respuesta) {
                loguear(`Impresora ${nombreImpresora} establecida correctamente`);
            } else {
                loguear(`No se pudo establecer la impresora con el nombre ${nombreImpresora}`);
            }
        });
};

const refrescarNombreDeImpresoraSeleccionada = () => {
    Impresora.getImpresora()
        .then(nombreImpresora => {
            $impresoraSeleccionada.textContent = nombreImpresora;
        });
}


$btnRefrescarLista.addEventListener("click", obtenerListaDeImpresoras);
$btnEstablecerImpresora.addEventListener("click", () => {
    const indice = $listaDeImpresoras.selectedIndex;
    if (indice === -1) return loguear("No hay ninguna impresora seleccionada")
    const opcionSeleccionada = $listaDeImpresoras.options[indice];
    establecerImpresoraComoPredeterminada(opcionSeleccionada.value);
});

$btnImprimir.addEventListener("click", () => {
    let impresora = new Impresora(RUTA_API);
    impresora.setFontSize(1, 1);
    impresora.setEmphasize(0);
    impresora.setAlign("center");
    impresora.write("Parzibyte's blog\n");
    impresora.cut();
    impresora.cutPartial();
    impresora.write("Blog de un programador\n");
    impresora.write("Telefono: 123456789\n");
    impresora.write("Fecha/Hora: 2019-08-01 13:21:22\n");
    impresora.write("--------------------------------\n");
    impresora.write("Venta de plugin para impresora\n");
    impresora.cut();
    impresora.cutPartial();
    impresora.setAlign("right");
    impresora.write("25 USD\n");
    impresora.write("--------------------------------\n");
    impresora.cut();
    impresora.cutPartial();
    impresora.write("TOTAL: 25 USD\n");
    impresora.write("--------------------------------\n");
    impresora.setAlign("center");
    impresora.write("***Gracias por su compra***");
    impresora.cut();
    impresora.cutPartial(); // Pongo este y también cut porque en ocasiones no funciona con cut, solo con cutPartial
    impresora.end()
        .then(valor => {
            loguear("Al imprimir: " + valor);
        })
});

// En el init, obtenemos la lista
obtenerListaDeImpresoras();
// Y también la impresora seleccionada
refrescarNombreDeImpresoraSeleccionada();
    </script>    
</body>

</html>