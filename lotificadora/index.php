
<?php
include("conexion.php");

$mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $accion = $_POST["accion"];

    try {

        if ($accion == "cliente") {

            $nombre = $_POST["nombre"];
            $dpi = $_POST["dpi"];
            $telefono = $_POST["telefono"];

            $sql = "INSERT INTO clientes
                    (nombre, dpi, telefono)
                    VALUES (?, ?, ?)";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([$nombre, $dpi, $telefono]);

            $mensaje = "Cliente guardado";
        }

        if ($accion == "proyecto") {

            $nombre = $_POST["nombre"];
            $ubicacion = $_POST["ubicacion"];

            $sql = "INSERT INTO proyectos
                    (nombre, ubicacion)
                    VALUES (?, ?)";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([$nombre, $ubicacion]);

            $mensaje = "Proyecto guardado";
        }

        if ($accion == "lote") {

            $proyecto = (int)$_POST["proyecto"];
            $numero = trim($_POST["numero"]);
            $precio = (float)$_POST["precio"];

            if ($proyecto <= 0 || $numero == "" || $precio <= 0) {
                throw new Exception("Datos del lote incorrectos");
            }

            $sql = "INSERT INTO lotes
                    (id_proyecto, numero_lote, precio)
                    VALUES (?, ?, ?)";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([$proyecto, $numero, $precio]);

            $mensaje = "Lote guardado";
        }

        if ($accion == "financiamiento") {

            $cliente = (int)$_POST["cliente"];
            $lote = (int)$_POST["lote"];
            $monto = round((float)$_POST["monto"], 2);
            $meses = (int)$_POST["meses"];

            if ($cliente <= 0 || $lote <= 0 ||
                $monto <= 0 || $meses < 1 || $meses > 360) {
                throw new Exception("Datos incorrectos");
            }

            $pdo->beginTransaction();

            
            $sql = "SELECT COUNT(*) FROM financiamientos
                    WHERE id_lote = ?";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([$lote]);

            if ($consulta->fetchColumn() > 0) {
                throw new Exception("Este lote ya tiene financiamiento");
            }

            $sql = "INSERT INTO financiamientos
                    (id_cliente, id_lote, monto_financiado,
                     plazo_meses, tasa_interes, saldo_pendiente)
                    VALUES (?, ?, ?, ?, 0, ?)";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([
                $cliente, $lote, $monto, $meses, $monto
            ]);

            $id_nuevo = $pdo->lastInsertId();

            
            $centavos = (int)round($monto * 100);
            $base = intdiv($centavos, $meses);

            for ($i = 1; $i <= $meses; $i++) {

                $valor = $base;

                if ($i == $meses) {
                    $valor = $centavos - $base * ($meses - 1);
                }

                $cuota = $valor / 100;
                $fecha = date("Y-m-d", strtotime("+$i month"));

                $sql = "INSERT INTO cuotas
                        (id_financiamiento, numero_cuota,
                         fecha_vencimiento, monto_cuota, saldo_cuota)
                        VALUES (?, ?, ?, ?, ?)";

                $consulta = $pdo->prepare($sql);
                $consulta->execute([
                    $id_nuevo, $i, $fecha, $cuota, $cuota
                ]);
            }

            $pdo->commit();

            $mensaje = "Financiamiento guardado";
        }

    } catch (Exception $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $mensaje = "Error: " . $e->getMessage();
    }
}

$clientes = $pdo->query("SELECT * FROM clientes")->fetchAll();


$proyectos = $pdo->query("SELECT * FROM proyectos")->fetchAll();


$lotes = $pdo->query("SELECT * FROM lotes")->fetchAll();


$financiamientos = $pdo->query("
    SELECT f.id_financiamiento, c.nombre, l.numero_lote
    FROM financiamientos f
    INNER JOIN clientes c ON f.id_cliente = c.id_cliente
    INNER JOIN lotes l ON f.id_lote = l.id_lote
")->fetchAll(PDO::FETCH_ASSOC);


$id = (int)($_GET["id"] ?? 0);

$sql = "SELECT c.nombre, l.numero_lote,
        f.id_financiamiento, f.saldo_pendiente
        FROM financiamientos f
        INNER JOIN clientes c
        ON f.id_cliente = c.id_cliente
        INNER JOIN lotes l
        ON f.id_lote = l.id_lote";

if ($id > 0) {
    $sql .= " WHERE f.id_financiamiento = ?";
    $consulta = $pdo->prepare($sql);
    $consulta->execute([$id]);
    $datos = $consulta->fetch(PDO::FETCH_ASSOC);
} else {
    $sql .= " LIMIT 1";
    $datos = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lotificacion La Bendicion</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>Lotificacion La Bendicion</h1>
    <p>Sistema de control de pagos</p>
</header>

<section class="contenedor">

    <?php if ($mensaje != "") { ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php } ?>

    <h2>Registro de cobros</h2>

    <form method="GET" action="index.php">

        <label for="buscar">Seleccione su  cliente:</label>
        <select name="id" id="buscar">

            <?php foreach ($financiamientos as $f) { ?>
                <option
                    value="<?php echo $f['id_financiamiento']; ?>"
                    <?php
                    if ($datos && $datos['id_financiamiento'] ==
                        $f['id_financiamiento']) echo "selected";
                    ?>
                >
                    <?php echo htmlspecialchars($f['nombre']); ?>
                    -
                    <?php echo htmlspecialchars($f['numero_lote']); ?>
                </option>
            <?php } ?>

        </select>

        <button type="submit">Buscar cliente</button>
    </form>

    <?php if ($datos) { ?>

    <form action="guardar_pago.php" method="POST">

        <input type="hidden"
               name="id_financiamiento"
               value="<?php echo $datos['id_financiamiento']; ?>">

        <label for="cliente_cobro">Nombre del cliente:</label>
        <input type="text" id="cliente_cobro"
               value="<?php echo htmlspecialchars($datos['nombre']); ?>"
               readonly>

        <label for="lote_cobro">Numero de lote:</label>
        <input type="text" id="lote_cobro"
               value="<?php echo htmlspecialchars($datos['numero_lote']); ?>"
               readonly>

        <label for="saldo">Saldo pendiente:</label>
        <input type="text" id="saldo"
               value="<?php echo $datos['saldo_pendiente']; ?>"
               readonly>

        <label for="tipo">Tipo de pago:</label>
        <select name="tipo" id="tipo">
            <option value="MESES">Varios meses</option>
            <option value="CAPITAL">Abono a capital</option>
            <option value="VARIABLE">Pago variable</option>
        </select>

        <label for="meses_cobro">Cantidad de meses:</label>
        <input type="number" name="meses"
               id="meses_cobro" min="1" value="1">

        <label for="monto_cobro">Monto a pagar:</label>
        <input type="number" name="monto"
               id="monto_cobro" step="0.01"
               min="0.01" placeholder="Ingrese monto">

        <button type="submit">Guardar pago</button>

    </form>

    <?php } else { ?>
        <p>No hay financiamientos para cobrar.</p>
    <?php } ?>

    <?php if (isset($_GET['ok'])) { ?>
        <p>Pago registrado correctamente.</p>
    <?php } ?>

    <hr>

    <h2>Registro de clientes</h2>

    <form action="index.php" method="POST">

        <input type="hidden" name="accion" value="cliente">

        <label for="nombre">Nombre del cliente:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="dpi">DPI:</label>
        <input type="text" id="dpi" name="dpi" maxlength="13">

        <label for="telefono">Telefono:</label>
        <input type="text" id="telefono" name="telefono">

        <button type="submit">Guardar cliente</button>

    </form>

    <hr>

    <h2>Registro de proyectos</h2>

    <form action="index.php" method="POST">

        <input type="hidden" name="accion" value="proyecto">

        <label for="nombre_proyecto">Nombre del proyecto:</label>
        <input type="text" id="nombre_proyecto"
               name="nombre" required>

        <label for="ubicacion">Ubicacion:</label>
        <input type="text" id="ubicacion" name="ubicacion">

        <button type="submit">Guardar proyecto</button>

    </form>

    <hr>

    <h2>Registro de lotes</h2>

    <form action="index.php" method="POST">

        <input type="hidden" name="accion" value="lote">

        <label for="proyecto">Proyecto:</label>
        <select id="proyecto" name="proyecto" required>

            <?php foreach ($proyectos as $p) { ?>
                <option value="<?php echo $p['id_proyecto']; ?>">
                    <?php echo htmlspecialchars($p['nombre']); ?>
                </option>
            <?php } ?>

        </select>

        <label for="numero">Numero de lote:</label>
        <input type="text" id="numero" name="numero" required>

        <label for="precio">Precio del lote:</label>
        <input type="number" id="precio" name="precio"
               step="0.01" min="0.01" required>

        <button type="submit">Guardar lote</button>

    </form>

    <hr>

    <h2>Registro de financiamientos</h2>

    <form action="index.php" method="POST">

        <input type="hidden" name="accion"
               value="financiamiento">

        <label for="cliente_fin">Cliente:</label>
        <select id="cliente_fin" name="cliente" required>

            <?php foreach ($clientes as $c) { ?>
                <option value="<?php echo $c['id_cliente']; ?>">
                    <?php echo htmlspecialchars($c['nombre']); ?>
                </option>
            <?php } ?>

        </select>

        <label for="lote_fin">Lote:</label>
        <select id="lote_fin" name="lote" required>

            <?php foreach ($lotes as $l) { ?>
                <option value="<?php echo $l['id_lote']; ?>">
                    <?php echo htmlspecialchars($l['numero_lote']); ?>
                </option>
            <?php } ?>

        </select>

        <label for="monto_fin">Monto financiado:</label>
        <input type="number" id="monto_fin" name="monto"
               step="0.01" min="0.01" required>

        <label for="meses_fin">Cantidad de meses:</label>
        <input type="number" id="meses_fin" name="meses"
               min="1" max="360" required>

        <button type="submit">Guardar financiamiento</button>

    </form>

</section>

</body>
</html>
