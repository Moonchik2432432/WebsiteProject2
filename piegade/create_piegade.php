<?php include "../checkAutenfikacija.php";?>

<?php
require_once "../config.php";

// Получаем списки для выпадающих селектов
$klients_options = [];
$res = mysqli_query($link, "SELECT id, Uznenuma_nosaukums FROM Klients");
while ($row = mysqli_fetch_assoc($res)) {
    $klients_options[$row['id']] = $row['Uznenuma_nosaukums'];
}

$darbinieks_options = [];
$res = mysqli_query($link, "SELECT id, Vards, Uzvards FROM Darbinieks"); 
while ($row = mysqli_fetch_assoc($res)) {
    $darbinieks_options[$row['id']] = $row['Vards'] . ' ' . $row['Uzvards'];
}

$tvertne_options = [];
$res = mysqli_query($link, "SELECT id, Nosaukums FROM Tvertne");
while ($row = mysqli_fetch_assoc($res)) {
    $tvertne_options[$row['id']] = $row['Nosaukums'];
}

// Переменные
$piegades_datums = $summa = $daudzums = "";
$klients_id = $darbinieks_id = $tvertne_id = "";

$errors = [
    'piegades_datums' => '',
    'piegade_summa' => '',
    'daudzums_tvertni' => '',
    'klients_id' => '',
    'darbinieks_id' => '',
    'tvertne_id' => ''
];

// Обработка POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Дата
    $input_date = trim($_POST["PiegadesDatums"]);
    if (empty($input_date)) {
        $errors['piegades_datums'] = "Lūdzu, ievadiet piegādes datumu.";
    } else {
        $piegades_datums = $input_date;
    }

    // Summa (цена)
    $input_summa = trim($_POST["Summa"]);
    if (empty($input_summa)) {
        $errors['piegade_summa'] = "Lūdzu, ievadiet summu.";
    } elseif (!is_numeric($input_summa) || $input_summa < 0) {
        $errors['piegade_summa'] = "Lūdzu, ievadiet derīgu summu.";
    } else {
        $summa = $input_summa;
    }

    // Daudzums (количество)
    $input_daudzums = trim($_POST["Daudzums"]);
    if (empty($input_daudzums)) {
        $errors['daudzums_tvertni'] = "Lūdzu, ievadiet daudzumu.";
    } elseif (!is_numeric($input_daudzums) || $input_daudzums < 0) {
        $errors['daudzums_tvertni'] = "Lūdzu, ievadiet derīgu daudzumu.";
    } else {
        $daudzums = $input_daudzums;
    }

    // Klients_ID
    $input_klients = $_POST["Klients_ID"] ?? "";
    if (empty($input_klients) || !array_key_exists($input_klients, $klients_options)) {
        $errors['klients_id'] = "Lūdzu, izvēlieties piegādātāju.";
    } else {
        $klients_id = $input_klients;
    }

    // Darbinieks_ID (Piegades_darbinieks_ID)
    $input_darbinieks = $_POST["Pasutijuma_sanemejs_ID"] ?? "";
    if (empty($input_darbinieks) || !array_key_exists($input_darbinieks, $darbinieks_options)) {
        $errors['darbinieks_id'] = "Lūdzu, izvēlieties pasūtījuma saņēmēju.";
    } else {
        $darbinieks_id = $input_darbinieks;
    }

    // Tvertne_ID
    $input_tvertne = $_POST["Tvertne_ID"] ?? "";
    if (empty($input_tvertne) || !array_key_exists($input_tvertne, $tvertne_options)) {
        $errors['tvertne_id'] = "Lūdzu, izvēlieties tvertni.";
    } else {
        $tvertne_id = $input_tvertne;
    }

    // Если нет ошибок — вставляем
    if (!array_filter($errors)) {
        $sql = "INSERT INTO Piegade (Piegades_datums, Piegade_summa, Daudzums_tvertni, Klients_ID, Piegades_darbinieks_ID, Tvertne_ID) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sddiii", $piegades_datums, $summa, $daudzums, $klients_id, $darbinieks_id, $tvertne_id);

            if (mysqli_stmt_execute($stmt)) {
                header("location: piegade.php");
                exit();
            } else {
                echo "Kļūda saglabājot datus.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Pievienot jaunu piegādi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../navigation.php"; ?>
<?php include "../background.php";?>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="mt-4">Pievienot jaunu piegādi</h2>
                <p>Aizpildiet formu, lai pievienotu jaunu piegādi.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Piegādes datums</label>
                        <input type="date" name="PiegadesDatums" class="form-control <?php echo (!empty($errors['piegades_datums'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($piegades_datums); ?>">
                        <span class="invalid-feedback"><?php echo $errors['piegades_datums']; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Cena (€)</label>
                        <input type="number" step="0.01" name="Summa" class="form-control <?php echo (!empty($errors['piegade_summa'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($summa); ?>">
                        <span class="invalid-feedback"><?php echo $errors['piegade_summa']; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Daudzums</label>
                        <input type="number" step="1" name="Daudzums" class="form-control <?php echo (!empty($errors['daudzums_tvertni'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($daudzums); ?>">
                        <span class="invalid-feedback"><?php echo $errors['daudzums_tvertni']; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Piegādātājs</label>
                        <select name="Klients_ID" class="form-control <?php echo (!empty($errors['klients_id'])) ? 'is-invalid' : ''; ?>">
                            <option value="">Izvēlieties piegādātāju</option>
                            <?php foreach($klients_options as $id => $nosaukums): ?>
                                <option value="<?php echo $id; ?>" <?php if($klients_id == $id) echo 'selected'; ?>><?php echo htmlspecialchars($nosaukums); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $errors['klients_id']; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Pasūtījuma saņēmējs</label>
                        <select name="Pasutijuma_sanemejs_ID" class="form-control <?php echo (!empty($errors['darbinieks_id'])) ? 'is-invalid' : ''; ?>">
                            <option value="">Izvēlieties saņēmēju</option>
                            <?php foreach($darbinieks_options as $id => $vards): ?>
                                <option value="<?php echo $id; ?>" <?php if($darbinieks_id == $id) echo 'selected'; ?>><?php echo htmlspecialchars($vards); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $errors['darbinieks_id']; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Tvertne</label>
                        <select name="Tvertne_ID" class="form-control <?php echo (!empty($errors['tvertne_id'])) ? 'is-invalid' : ''; ?>">
                            <option value="">Izvēlieties tvertni</option>
                            <?php foreach($tvertne_options as $id => $nosaukums): ?>
                                <option value="<?php echo $id; ?>" <?php if($tvertne_id == $id) echo 'selected'; ?>><?php echo htmlspecialchars($nosaukums); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $errors['tvertne_id']; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Saglabāt">
                    <a href="piegade.php" class="btn btn-secondary ml-2">Atcelt</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
