<?php
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST["accion"] ?? "";

    try {
        if ($accion == "cliente") {
            $nombre = trim($_POST["nombre"] ?? "");
            $dpi = trim($_POST["dpi"] ?? "");
            $telefono = trim($_POST["telefono"] ?? "");
            if ($nombre == "") throw new Exception("Escriba el nombre.");

            $sql = "INSERT INTO clientes (nombre, dpi, telefono) VALUES (?, ?, ?)";
            $pdo->prepare($sql)->execute([$nombre, $dpi, $telefono]);
            $mensaje = "Cliente guardado.";
        }

        if ($accion == "proyecto") {
            $nombre = trim($_POST["nombre"] ?? "");
            $ubicacion = trim($_POST["ubicacion"] ?? "");
            if ($nombre == "") throw new Exception("Escriba el proyecto.");

            $sql = "INSERT INTO proyectos (nombre, ubicacion) VALUES (?, ?)";
            $pdo->prepare($sql)->execute([$nombre, $ubicacion]);
            $mensaje = "Proyecto guardado.";
        }

        if ($accion == "lote") {
            $proyecto = (int)($_POST["proyecto"] ?? 0);
            $numero = trim($_POST["numero"] ?? "");
            $precio = round((float)($_POST["precio"] ?? 0), 2);
            if ($proyecto <= 0 || $numero == "" || $precio <= 0) {
                throw new Exception("Revise los datos del lote.");
            }

            $sql = "INSERT INTO lotes (id_proyecto, numero_lote, precio)
                    VALUES (?, ?, ?)";
            $pdo->prepare($sql)->execute([$proyecto, $numero, $precio]);
            $mensaje = "Lote guardado.";
        }

        if ($accion == "financiamiento") {
            $cliente = (int)($_POST["cliente"] ?? 0);
            $lote = (int)($_POST["lote"] ?? 0);
            $monto = round((float)($_POST["monto"] ?? 0), 2);
            $meses = (int)($_POST["meses"] ?? 0);
            if ($cliente <= 0 || $lote <= 0 || $monto <= 0 || $meses < 1 || $meses > 120) {
                throw new Exception("Revise el cliente, lote, monto y meses.");
            }
            if ($monto < $meses * 0.01) {
                throw new Exception("El monto es demasiado bajo para tantas cuotas.");
            }

            $pdo->beginTransaction();

            // Bloquear el lote para evitar asignarlo dos veces.
            $sql = "SELECT precio FROM lotes WHERE id_lote = ? FOR UPDATE";
            $consulta = $pdo->prepare($sql);
            $consulta->execute([$lote]);
            $datosLote = $consulta->fetch();
            if (!$datosLote) throw new Exception("El lote no existe.");
            if ($monto > $datosLote['precio']) {
                throw new Exception("El financiamiento supera el precio del lote.");
            }

            $consulta = $pdo->prepare("SELECT COUNT(*) FROM financiamientos WHERE id_lote = ?");
            $consulta->execute([$lote]);
            if ($consulta->fetchColumn() > 0) {
                throw new Exception("Este lote ya tiene un financiamiento.");
            }

            $sql = "INSERT INTO financiamientos
                    (id_cliente, id_lote, monto_financiado, plazo_meses, tasa_interes, saldo_pendiente)
                    VALUES (?, ?, ?, ?, 0, ?)";
            $pdo->prepare($sql)->execute([$cliente, $lote, $monto, $meses, $monto]);
            $id = $pdo->lastInsertId();

            $totalCentavos = (int)round($monto * 100);
            $centavosNormales = intdiv($totalCentavos, $meses);
            $inicio = new DateTimeImmutable('first day of next month');
            for ($i = 1; $i <= $meses; $i++) {
                $centavosCuota = $i == $meses
                    ? $totalCentavos - $centavosNormales * ($meses - 1)
                    : $centavosNormales;
                $cuota = $centavosCuota / 100;
                $fecha = $inicio->modify('+' . ($i - 1) . ' months')->format('Y-m-d');

                $sql = "INSERT INTO cuotas
                        (id_financiamiento, numero_cuota, fecha_vencimiento, monto_cuota, saldo_cuota)
                        VALUES (?, ?, ?, ?, ?)";
                $pdo->prepare($sql)->execute([$id, $i, $fecha, $cuota, $cuota]);
            }
            $pdo->commit();