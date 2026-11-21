
<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = (int)$_POST["id_financiamiento"];
    $tipo = $_POST["tipo"];
    $meses = (int)$_POST["meses"];
    $monto = (float)$_POST["monto"];

    try {
        $pdo->beginTransaction();

        // Buscar financiamiento
        $sql = "SELECT * FROM financiamientos
                WHERE id_financiamiento = ? FOR UPDATE";

        $consulta = $pdo->prepare($sql);
        $consulta->execute([$id]);
        $datos = $consulta->fetch();

        if (!$datos) {
            throw new Exception("Financiamiento no encontrado");
        }

        // Calcular pago
        if ($tipo == "MESES") {
            if ($meses < 1 || $datos["plazo_meses"] <= 0) {
                throw new Exception("Meses incorrectos");
            }

            $cuota = $datos["monto_financiado"]
                   / $datos["plazo_meses"];

            $monto = round($cuota * $meses, 2);
        }

        if (!in_array($tipo, ["MESES", "CAPITAL", "VARIABLE"]) ||
            $monto <= 0 ||
            $monto > $datos["saldo_pendiente"]) {
            throw new Exception("Pago incorrecto");
        }

        //gp
        $sql = "INSERT INTO pagos
                (id_financiamiento, tipo_pago, monto_total)
                VALUES (?, ?, ?)";

        $consulta = $pdo->prepare($sql);
        $consulta->execute([$id, $tipo, $monto]);

        // Restar de la deuda
        $sql = "UPDATE financiamientos
                SET saldo_pendiente = saldo_pendiente - ?
                WHERE id_financiamiento = ?";

        $consulta = $pdo->prepare($sql);
        $consulta->execute([$monto, $id]);

        $pdo->commit();

        header("Location: index.php?ok=1");
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        echo htmlspecialchars($e->getMessage());
    }
}
?>
