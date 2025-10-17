<?php include "../checkAutenfikacija.php";?>

<?php
require_once "../config.php";

// Получаем список должностей
$amats_options = [];
$sql_amats = "SELECT id, Nosaukums FROM Amats";
if ($result_amats = mysqli_query($link, $sql_amats)) {
    while ($row = mysqli_fetch_assoc($result_amats)) {
        $amats_options[$row["id"]] = $row["Nosaukums"];
    }
    mysqli_free_result($result_amats);
}

// Переменные
$vards = $uzvards = $talrunis =  "";
$amats_id = "";
$vardsErr = $uzvardsErr = $amatsErr = $talrunisErr =  "";

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Валидация имени
    $input_vards = trim($_POST["vards"]);
    if (empty($input_vards)) {
        $vardsErr = "Lūdzu, ievadiet vārdu";
    } elseif (!preg_match('/^[\p{L}\s\-]+$/u', $input_vards)) {
        $vardsErr = "Lūdzu, ievadiet derīgu vārdu";
    } else {
        $vards = $input_vards;
    }

    // Валидация фамилии
    $input_uzvards = trim($_POST["uzvards"]);
    if (empty($input_uzvards)) {
        $uzvardsErr = "Lūdzu, ievadiet uzvārdu.";
    } elseif (!preg_match('/^[\p{L}\s\-]+$/u', $input_uzvards)) {
        $uzvardsErr = "Lūdzu, ievadiet derīgu uzvārdu";
    } else {
        $uzvards = $input_uzvards;
    }

    // Валидация amats_id
    $input_amats_id = $_POST["amats_id"] ?? "";
    if (empty($input_amats_id) || !array_key_exists($input_amats_id, $amats_options)) {
        $amatsErr = "Lūdzu, izvēlieties amatu.";
    } else {
        $amats_id = $input_amats_id;
    }

    // Валидация телефона
    $input_talrunis = trim($_POST["talrunis"]);
    if (empty($input_talrunis)) {
        $talrunisErr = "Lūdzu, ievadiet tālruņa numuru.";
    } elseif (!preg_match("/^[0-9]{8,15}$/", $input_talrunis)) {
        $talrunisErr = "Lūdzu, ievadiet derīgu tālruņa numuru.";
    } else {
        $talrunis = $input_talrunis;
    }

    // Если нет ошибок
    if (empty($vardsErr) && empty($uzvardsErr) && empty($amatsErr) && empty($talrunisErr)) {
        $sql = "INSERT INTO Darbinieks (Vards, Uzvards, Amats_ID, Talrunis) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssis", $param_vards, $param_uzvards, $param_amats_id, $param_talrunis);
            $param_vards = $vards;
            $param_uzvards = $uzvards;
            $param_amats_id = $amats_id;
            $param_talrunis = $talrunis;
            if (mysqli_stmt_execute($stmt)) {
                header("location: darbinieki.php");
                exit();
            } else {
                echo "Kļūda saglabājot ierakstu.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Jauns darbinieks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php";?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4">Jauns darbinieks</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Vārds</label>
                            <input type="text" name="vards" class="form-control <?php echo (!empty($vardsErr)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($vards); ?>">
                            <span class="invalid-feedback"><?php echo $vardsErr;?></span>
                        </div>
                        <div class="form-group">
                            <label>Uzvārds</label>
                            <input type="text" name="uzvards" class="form-control <?php echo (!empty($uzvardsErr)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($uzvards); ?>">
                            <span class="invalid-feedback"><?php echo $uzvardsErr;?></span>
                        </div>
                        <div class="form-group">
                            <label>Amats</label>
                            <select name="amats_id" class="form-control <?php echo (!empty($amatsErr)) ? 'is-invalid' : ''; ?>">
                                <option value="">Izvēlieties amatu</option>
                                <?php foreach($amats_options as $id => $nosaukums): ?>
                                    <option value="<?php echo $id; ?>" <?php if($amats_id == $id) echo 'selected'; ?>><?php echo htmlspecialchars($nosaukums); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $amatsErr;?></span>
                        </div>
                        <div class="form-group">
                            <label>Tālrunis</label>
                            <input type="text" name="talrunis" class="form-control <?php echo (!empty($talrunisErr)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($talrunis); ?>">
                            <span class="invalid-feedback"><?php echo $talrunisErr;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Saglabāt">
                        <a href="darbinieki.php" class="btn btn-secondary ml-2">Atcelt</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>