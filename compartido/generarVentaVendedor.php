<?php

if (!isset($_SESSION['correo'])) {
    header('Location: index.php');
    exit();
}

include "../compartido/conexion.php";

$queryCategorias = "SELECT * FROM categoria";
$resultCategorias = mysqli_query($conn, $queryCategorias);

if (!$resultCategorias) {
    echo "Error al obtener las categorías: " . mysqli_error($conn);
}

$categorias = array();
while ($rowCategoria = mysqli_fetch_assoc($resultCategorias)) {
    $categorias[$rowCategoria['idCategoria']] = $rowCategoria['nombreCategoria'];
}

if (isset($_POST['editar'])) {
    $idProducto = $_POST['id'];

    $query = "SELECT * FROM ventas WHERE idcodigo=$idcodigo";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $producto = $row['producto'];
    $precio_unitario = $row['precio'];
    $cantidad = $row['cantidad'];
    $descripcion = $row['descripcion'];
    $categoria = $row['categoria'];
}

if (isset($_POST['guardar'])) {
    include "editarVenta.php";
} else {

    $query = "SELECT * FROM Ventas";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error al obtener los productos: " . mysqli_error($conn);
    }
    ?>

    <h4 id="titulo-tabla">Ventas</h4>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <div id="buscar">
        <button id="buscar-venta"><i class="fas fa-search"></i></button>
        <input id="ip-buscar-venta" type="text">
    </div>
    <div id="tabla">
        <table>
            <tr>
                <th id="celda-principal">ID Venta</th>
                <th id="celda-principal">Fecha</th>
                <th id="celda-principal">ID producto</th>
                <th id="celda-principal">Producto</th>
                <th id="celda-principal">Descripción</th>
                <th id="celda-principal">Cantidad</th>
                <th id="celda-principal">Precio Unitario</th>
                <th id="celda-principal">Totales</th>
                <th id="celda-principal">Acciones</th>
            </tr>

            <?php
            $totalVentas = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['idVenta']; ?></td>
                    <td><?php echo $row['fecha_venta']; ?></td>
                    <td><?php echo $row['idcodigo']; ?></td>
                    <td><?php echo $row['producto']; ?></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td><?php echo $row['cantidad']; ?></td>
                    <td><?php echo $row['precio_unitario']; ?></td>

                    <?php
                    // Calcular el total de la fila y acumularlo
                    $totalFila = $row['cantidad'] * $row['precio_unitario'];
                    $totalVentas += $totalFila;
                    ?>

                    <td><?php echo $totalFila; ?></td>

                    <td class="acciones">
                        <a href="../Php/generar_pdf.php?id=<?php echo $row['idVenta']; ?>" target="_blank">Descargar PDF</a>
                    </td>
                </tr>
            <?php
            }
            ?>

            <!-- Imprimir una fila adicional para mostrar el total general -->
            <tr>
                <td colspan="6"></td>
                <td>Total: <?php echo $totalVentas; ?></td>
                <td></td>
            </tr>
        </table>
    </div>
    <script>
        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que deseas eliminar esta venta?");
        }
    </script>
    <script>
        $(document).ready(function() {
            // Agregar el evento de clic al botón de búsqueda de ventas
            $("#buscar-venta").click(function() {
                // Obtener el valor del campo de búsqueda de ventas
                var searchTerm = $("#ip-buscar-venta").val();

                // Realizar la petición AJAX al servidor para buscar ventas
                $.ajax({
                    url: "../compartido/buscarVentaVendedor.php",
                    method: "POST",
                    data: { searchTerm: searchTerm },
                    success: function(response) {
                        // Actualizar la tabla de ventas con los resultados de la búsqueda
                        $("#tabla table").html(response);
                    }
                });
            });
        });
    </script>
    <?php
}
?>