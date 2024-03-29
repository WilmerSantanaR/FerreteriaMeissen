<?php
include "conexion.php";

if (isset($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];

    // Realizar la consulta a la base de datos con la condición de búsqueda
    $query = "SELECT * FROM proveedor WHERE idProveedor LIKE '%$searchTerm%' OR nombreProveedor LIKE '%$searchTerm%' OR telefonoProveedor LIKE '%$searchTerm%' OR direccionProveedor LIKE '%$searchTerm%' OR correoProveedor LIKE '%$searchTerm%'";
    
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo '<div class ="mensajes-alertas">¡Error al obtener los proveedores.
        <div class ="mensaje-boton"><a href="../Php/gestionarProveedores.php">Aceptar</a></div>'. mysqli_error($conn);
    } else {
        // Construir la tabla con los resultados de la búsqueda
        $table = '';

        // Bandera para verificar si ya se ha impreso el encabezado
        $encabezadoImpreso = false;

        while ($row = mysqli_fetch_assoc($result)) {
            // Imprimir encabezado solo si no se ha impreso antes
            if (!$encabezadoImpreso) {
                $table .= '<tr>
                    <th id="celda-principal">Identificación</th>
                    <th id="celda-principal">Nombre</th>
                    <th id="celda-principal">Apellido</th>
                    <th id="celda-principal">Teléfono</th>
                    <th id="celda-principal">Dirección</th>
                    <th id="celda-principal">Correo</th>
                </tr>';
                // Marcar la bandera como true para que no se imprima de nuevo
                $encabezadoImpreso = true;
            }

            $idProveedor = $row['idProveedor'];
            $table .= '<tr>
                <td>' . $row['idProveedor'] . '</td>
                <td>' . $row['nombreProveedor'] . '</td>
                <td>' . $row['apellidoProveedor'] . '</td>
                <td>' . $row['telefonoProveedor'] . '</td>
                <td>' . $row['direccionProveedor'] . '</td>
                <td>' . $row['correoProveedor'] . '</td>
            </tr>';
        }
        echo $table;
    }
}
?>
<script>
    function confirmarEliminacion() {
        return confirm("¿Estás seguro de que deseas eliminar este cliente?");
    }
</script>
