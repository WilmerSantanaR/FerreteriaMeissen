<?php
include "../compartido/conexion.php";

$query = "SELECT p.idProducto, 
                 p.stockProducto + COALESCE(entradas.cantidad_entrante, 0) - COALESCE(salidas.cantidad_saliente, 0) AS nuevo_stock
          FROM productos p
          LEFT JOIN (
              SELECT idProducto, SUM(cantidad) AS cantidad_entrante
              FROM ventas
              WHERE tipo = 'entrada'
              GROUP BY idProducto
          ) AS entradas ON p.idProducto = entradas.idProducto
          LEFT JOIN (
              SELECT idProducto, SUM(cantidad) AS cantidad_saliente
              FROM ventas
              WHERE tipo = 'salida'
              GROUP BY idProducto
          ) AS salidas ON p.idProducto = salidas.idProducto";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error al obtener datos de ventas: " . mysqli_error($conn);
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $idProducto = $row['idProducto'];
        $nuevoStock = $row['nuevo_stock'];

        // Actualizar el stock en la tabla de productos
        $updateQuery = "UPDATE productos SET stockProducto = $nuevoStock WHERE idProducto = $idProducto";
        $updateResult = mysqli_query($conn, $updateQuery);

        if (!$updateResult) {
            echo "Error al actualizar el stock del producto $idProducto: " . mysqli_error($conn);
        }
    }
}
?>
