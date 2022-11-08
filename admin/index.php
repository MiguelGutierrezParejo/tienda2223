<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de articulos</title>
</head>
<body>
    <?php
    require 'auxiliar.php';

    $desde_codigo = obtener_get('desde_codigo');
    $hasta_codigo = obtener_get('hasta_codigo');
    $descripcion = obtener_get('descripcion');
    $desde_precio = obtener_get('desde_precio');
    $hasta_precio = obtener_get('hasta_precio');


    ?>
    <div>
        <form action="" method="get">
            <fieldset>
                <p>
                    <label>
                        Desde código:
                        <input type="text" name="desde_codigo" size="8" value="<?= hh($desde_codigo) ?>">
                    </label>
                </p>
                <p>
                    <label>
                        Hasta código:
                        <input type="text" name="hasta_codigo" size="8" value="<?= hh($hasta_codigo) ?>">
                    </label>
                </p>
                <p>
                    <label>
                        Descripción:
                        <input type="text" name="descripcion" value="<?= hh($descripcion) ?>">
                    </label>
                </p>
                <p>
                    <label>
                        Desde precio:
                        <input type="text" name="desde_precio" value="<?= hh($desde_precio) ?>">
                    </label>
                </p>
                <p>
                    <label>
                        Hasta precio:
                        <input type="text" name="hasta_precio" value="<?= hh($hasta_precio) ?>">
                    </label>
                </p>
                <button type="submit">Buscar</button>
            </fieldset>
        </form>
    </div>
    <?php
    $pdo = conectar();
    $pdo->beginTransaction();
    $pdo->exec('LOCK TABLE articulos IN SHARE MODE');
    $where = [];
    $execute = [];
    if (isset($desde_codigo) && $desde_codigo != '') {
        $where[] = 'codigo >= :desde_codigo';
        $execute[':desde_codigo'] = $desde_codigo;
    }
    if (isset($hasta_codigo) && $hasta_codigo != '') {
        $where[] = 'codigo <= :hasta_codigo';
        $execute[':hasta_codigo'] = $hasta_codigo;
    }
    if (isset($descripcion) && $descripcion != '') {
        $where[] = 'lower(descripcion) LIKE lower(:descripcion)';
        $execute[':descripcion'] = "%$descripcion%";
    }
    if (isset($desde_precio) && $desde_precio != '') {
        $where[] = 'precio >= :desde_precio';
        $execute[':desde_precio'] = $desde_precio;
    }
    if (isset($hasta_precio) && $hasta_precio != '') {
        $where[] = 'precio <= :hasta_precio';
        $execute[':hasta_precio'] = $hasta_precio;
    }
    $where = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    $sent = $pdo->prepare("SELECT COUNT(*) FROM articulos $where");
    $sent->execute($execute);
    $total = $sent->fetchColumn();
    $sent = $pdo->prepare("SELECT * FROM articulos $where ORDER BY codigo");
    $sent->execute($execute);
    $pdo->commit();
    ?>
    <br>
    <div>
        <table style="margin: auto" border="1">
            <thead>
                <th>Código</th>
                <th>Descripción</th>
                <th>Precio</th>
            </thead>
            <tbody>
                <?php foreach ($sent as $fila): ?>
                    <tr>
                        <td><?= hh($fila['codigo']) ?></td>
                        <td><?= hh($fila['descripcion']) ?></td>
                        <td><?= hh($fila['precio']) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</body>
</html>