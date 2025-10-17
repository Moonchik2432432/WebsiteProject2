<?php
require_once "../config.php";

// Инициализация переменных и ошибок
$Piegades_datums = $Piegade_summa = $Daudzums_tvertni = $Klients_ID = $Piegades_darbinieks_ID = $Tvertne_ID = "";
$Piegades_datums_err = $Piegade_summa_err = $Daudzums_tvertni_err = $Klients_ID_err = $Piegades_darbinieks_ID_err = $Tvertne_ID_err = "";

// Получение списков для селектов
$klienti = [];
$res = mysqli_query($link, "SELECT id, Uznenuma_nosaukums FROM Klients");
while ($row = mysqli_fetch_assoc($res)) {
    $klienti[$row['id']] = $row['Uznenuma_nosaukums'];
}

$darbinieki = [];
$res = mysqli_query($link, "SELECT id, Vards, Uzvards FROM Darbinieks");
while ($row = mysqli_fetch_assoc($res)) {
    $darbinieki[$row['id']] = $row['Vards'] . ' ' . $row['Uzvards'];
}

$tvertnes = [];
$res = mysqli_query($link, "SELECT id, Nosaukums FROM Tvertne");
while ($row = mysqli_fetch_assoc($res)) {
    $tvertnes[$row['id']] = $row['Nosaukums'];
}

// Обработка POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = (int) $_POST["id"];

    $Piegades_datums = trim($_POST["Piegades_datums"]);
    $Piegade_summa = trim($_POST["Piegade_summa"]);
    $Daudzums_tvertni = trim($_POST["Daudzums_tvertni"]);
    $Klients_ID = trim($_POST["Klients_ID"]);
    $Piegades_darbinieks_ID = trim($_POST["Piegades_darbinieks_ID"]);
    $Tvertne_ID = trim($_POST["Tvertne_ID"]);

    // Валидация
    if (empty($Piegades_datums)) $Piegades_datums_err = "Lūdzu ievadiet piegādes datumu.";
    if (!is_numeric($Piegade_summa)) $Piegade_summa_err = "Lūdzu ievadiet derīgu summu.";
    if (!ctype_digit($Daudzums_tvertni)) $Daudzums_tvertni_err = "Daudzumam jābūt veselam skaitlim.";
    if (!isset($klienti[$Klients_ID])) $Klients_ID_err = "Izvēlieties derīgu klientu.";
    if (!isset($darbinieki[$Piegades_darbinieks_ID])) $Piegades_darbinieks_ID_err = "Izvēlieties darbinieku.";
    if (!isset($tvertnes[$Tvertne_ID])) $Tvertne_ID_err = "Izvēlieties tvertni.";

    // Если нет ошибок
    if (empty($Piegades_datums_err) && empty($Piegade_summa_err) && empty($Daudzums_tvertni_err)
        && empty($Klients_ID_err) && empty($Piegades_darbinieks_ID_err) && empty($Tvertne_ID_err)) {
        
        $sql = "UPDATE Piegade 
                SET Piegades_datums = ?, Piegade_summa = ?, Daudzums_tvertni = ?, 
                    Klients_ID = ?, Piegades_darbinieks_ID = ?, Tvertne_ID = ? 
                WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sdiiiii", $Piegades_datums, $Piegade_summa, $Daudzums_tvertni, 
                                   $Klients_ID, $Piegades_darbinieks_ID, $Tvertne_ID, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("location: piegade.php");
                exit();
            } else {
                echo "Kļūda saglabājot datus: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        }
    }
} elseif (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
    $sql = "SELECT * FROM Piegade WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $Piegades_datums = $row["Piegades_datums"];
                $Piegade_summa = $row["Piegade_summa"];
                $Daudzums_tvertni = $row["Daudzums_tvertni"];
                $Klients_ID = $row["Klients_ID"];
                $Piegades_darbinieks_ID = $row["Piegades_darbinieks_ID"];
                $Tvertne_ID = $row["Tvertne_ID"];
            } else {
                echo "Nav atrasta piegāde ar šo ID.";
                exit();
            }
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Labot piegādi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php";?>
<div class="wrapper">
<div class="container mt-4">
    <div class="container-fluid">
    <h2>Labot piegādi</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">
        <div class="form-group">
            <label>Piegādes datums</label>
            <input type="date" name="Piegades_datums" class="form-control <?php echo (!empty($Piegades_datums_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($Piegades_datums); ?>">
            <span class="invalid-feedback"><?php echo $Piegades_datums_err; ?></span>
        </div>
        <div class="form-group">
            <label>Summa (€)</label>
            <input type="text" name="Piegade_summa" class="form-control <?php echo (!empty($Piegade_summa_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($Piegade_summa); ?>">
            <span class="invalid-feedback"><?php echo $Piegade_summa_err; ?></span>
        </div>
        <div class="form-group">
            <label>Daudzums</label>
            <input type="number" name="Daudzums_tvertni" class="form-control <?php echo (!empty($Daudzums_tvertni_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($Daudzums_tvertni); ?>">
            <span class="invalid-feedback"><?php echo $Daudzums_tvertni_err; ?></span>
        </div>
        <div class="form-group">
            <label>Klients (piegādātājs)</label>
            <select name="Klients_ID" class="form-control <?php echo (!empty($Klients_ID_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Izvēlieties klientu</option>
        <?php foreach ($klienti as $klients_id => $nos): ?>
            <option value="<?php echo $klients_id; ?>" <?php echo ($Klients_ID == $klients_id) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($nos); ?>
            </option>
        <?php endforeach; ?>
            </select>
            <span class="invalid-feedback"><?php echo $Klients_ID_err; ?></span>
        </div>
        <div class="form-group">
            <label>Darbinieks</label>
            <select name="Piegades_darbinieks_ID" class="form-control <?php echo (!empty($Piegades_darbinieks_ID_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Izvēlieties darbinieku</option>
        <?php foreach ($darbinieki as $darbinieks_id => $name): ?>
            <option value="<?php echo $darbinieks_id; ?>" <?php echo ($Piegades_darbinieks_ID == $darbinieks_id) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($name); ?>
            </option>
        <?php endforeach; ?>
            </select>
            <span class="invalid-feedback"><?php echo $Piegades_darbinieks_ID_err; ?></span>
        </div>
        <div class="form-group">
            <label>Tvertne</label>
            <select name="Tvertne_ID" class="form-control <?php echo (!empty($Tvertne_ID_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Izvēlieties tvertni</option>
        <?php foreach ($tvertnes as $tvertne_id => $name): ?>
            <option value="<?php echo $tvertne_id; ?>" <?php echo ($Tvertne_ID == $tvertne_id) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($name); ?>
            </option>
        <?php endforeach; ?>
            </select>
            <span class="invalid-feedback"><?php echo $Tvertne_ID_err; ?></span>
        </div>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <input type="submit" class="btn btn-primary" value="Saglabāt">
        <a href="piegade.php" class="btn btn-secondary ml-2">Atcelt</a>
    </form>
    </div>
    </div>
</div>
</body>
</html>
