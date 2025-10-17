<?php include "../checkAutenfikacija.php";?>

<?php
require_once "../config.php";

// Переменные
$nosaukums = $udensApjoms = "";
$nosaukums_err = $udensApjoms_err = "";

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Валидация Nosaukums
    $input_nosaukums = trim($_POST["Nosaukums"]);
    if (empty($input_nosaukums)) {
        $nosaukums_err = "Lūdzu, ievadiet nosaukumu.";
    } else {
        $nosaukums = $input_nosaukums;
    }

    // Валидация UdensApjoms_L (целое число больше 0)
    $input_udensApjoms = trim($_POST["UdensApjoms_L"]);
    if (empty($input_udensApjoms)) {
        $udensApjoms_err = "Lūdzu, ievadiet ūdens apjomu.";
    } elseif (!filter_var($input_udensApjoms, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        $udensApjoms_err = "Lūdzu, ievadiet korektu ūdens apjomu (veselu pozitīvu skaitli).";
    } else {
        $udensApjoms = $input_udensApjoms;
    }

    // Если нет ошибок, вставляем в базу
    if (empty($nosaukums_err) && empty($udensApjoms_err)) {
        $sql = "INSERT INTO Tvertne (Nosaukums, UdensApjoms_L) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $nosaukums, $udensApjoms);
            if (mysqli_stmt_execute($stmt)) {
                header("location: tvertni.php");
                exit();
            } else {
                echo "Kļūda saglabājot datus: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Kļūda sagatavošanā: " . mysqli_error($link);
        }
    }

    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Izveidot Tvertni</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php";?>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Izveidot Tvertni</h2>
                <p>Lūdzu aizpildiet šo formu, lai pievienotu jaunu tvertni datubāzē.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Nosaukums</label>
                        <input type="text" name="Nosaukums" class="form-control <?php echo (!empty($nosaukums_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($nosaukums); ?>">
                        <span class="invalid-feedback"><?php echo $nosaukums_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Ūdens apjoms (L)</label>
                        <input type="text" name="UdensApjoms_L" class="form-control <?php echo (!empty($udensApjoms_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($udensApjoms); ?>">
                        <span class="invalid-feedback"><?php echo $udensApjoms_err; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Saglabāt">
                    <a href="tvertni.php" class="btn btn-secondary ml-2">Atcelt</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
