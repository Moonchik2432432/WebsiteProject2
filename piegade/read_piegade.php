<?php include "../checkAutenfikacija.php";?>

<?php
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    require_once "../config.php";

    // Запрос с JOIN для получения связанных данных с правильными именами полей
    $sql = "SELECT p.*, 
                   k.Uznenuma_nosaukums AS Piegadatajs_Nosaukums, 
                   d.Vards AS Pasutijuma_sanemejs_Vards, 
                   t.Nosaukums AS Tvertne_Nosaukums
            FROM Piegade p
            LEFT JOIN Klients k ON p.Klients_ID = k.id
            LEFT JOIN Darbinieks d ON p.Piegades_darbinieks_ID = d.id
            LEFT JOIN Tvertne t ON p.Tvertne_ID = t.id
            WHERE p.id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        $param_id = trim($_GET["id"]);
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
            } else {
                header("location: ../error.php");
                exit();
            }
        } else {
            echo "Kļūda vaicājuma izpildē.";
            exit();
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);
} else {
    header("location: ../error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Piegādes detaļas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php";?>
<div class="wrapper">
    <div class="container-fluid">
        <h2 class="mt-4 mb-3">Piegādes detaļas</h2>
        <div class="form-group">
            <label>Piegādes datums</label>
            <p><b><?php echo htmlspecialchars($row["Piegades_datums"]); ?></b></p>
        </div>
        <div class="form-group">
            <label>Cena (€)</label>
            <p><b><?php echo htmlspecialchars($row["Piegade_summa"]); ?></b></p>
        </div>
        <div class="form-group">
            <label>Daudzums</label>
            <p><b><?php echo htmlspecialchars($row["Daudzums_tvertni"]); ?></b></p>
        </div>
        <div class="form-group">
            <label>Piegādātājs</label>
            <p><b><?php echo htmlspecialchars($row["Piegadatajs_Nosaukums"] ?? 'N/A'); ?></b></p>
        </div>
        <div class="form-group">
            <label>Pasūtījuma saņēmējs</label>
            <p><b><?php echo htmlspecialchars($row["Pasutijuma_sanemejs_Vards"] ?? 'N/A'); ?></b></p>
        </div>
        <div class="form-group">
            <label>Tvertne</label>
            <p><b><?php echo htmlspecialchars($row["Tvertne_Nosaukums"] ?? 'N/A'); ?></b></p>
        </div>
        <a href="piegade.php" class="btn btn-primary">Atpakaļ</a>
    </div>
</div>
</body>
</html>
