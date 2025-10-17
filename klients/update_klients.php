<?php include "../checkAutenfikacija.php";?>

<?php
require_once "../config.php";

// Инициализация переменных
$nosaukums = $adrese = $talrunis = "";
$nosaukums_err = $adrese_err = $talrunis_err = "";

// Обработка отправки формы
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $id = $_POST["id"];

    // Валидация
    $input_nosaukums = trim($_POST["Uznenuma_nosaukums"]);
    if (empty($input_nosaukums)) {
        $nosaukums_err = "Lūdzu ievadiet nosaukums.";
    } else {
        $nosaukums = $input_nosaukums;
    }

    $input_adrese = trim($_POST["Adrese"]);
    if (empty($input_adrese)) {
        $adrese_err = "Lūdzu ievadiet adrese.";
    } else {
        $adrese = $input_adrese;
    }

$input_talrunis = trim($_POST["Talrunis"]);

    if (!preg_match('/^[0-9]{1,10}$/', $input_talrunis)) {
        $talrunis_err = "Ievadiet korektu tālruņa numuru, maksimums 10 cipari.";
    } elseif (bccomp($input_talrunis, '2147483647') === 1) {
        $talrunis_err = "Numurs pārsniedz maksimālo atļauto vērtību.";
    } else {
        $talrunis = (int)$input_talrunis;
    }

    if (empty($nosaukums_err) && empty($adrese_err) && empty($talrunis_err)) {
        $sql = "UPDATE Klients SET Uznenuma_nosaukums=?, Adrese=?,Talrunis=? WHERE id=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssi", $nosaukums, $adrese, $talrunis, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("location: klienti.php");
                exit();
            } else {
                echo "Kļūda saglabājot datus.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
} else {
    // Получение данных для формы при GET-запросе
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id = trim($_GET["id"]);
        $sql = "SELECT * FROM Klients WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $nosaukums = $row["Uznenuma_nosaukums"];
                    $adrese = $row["Adrese"];
                    $talrunis = $row["Talrunis"];
                } else {
                    header("location: ../error.php");
                    exit();
                }
            } else {
                echo "Kļūda vaicājumā.";
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($link);
    } else {
        header("location: ../error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Labot piegadataju</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php";?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4 mb-3">Labot piegadataju</h2>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Nosaukums</label>
                            <input type="text" name="Uznenuma_nosaukums" class="form-control <?php echo (!empty($nosaukums_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($nosaukums); ?>">
                            <span class="invalid-feedback"><?php echo $nosaukums_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Adrese</label>
                            <input type="text" name="Adrese" class="form-control <?php echo (!empty($adrese_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($adrese); ?>">
                            <span class="invalid-feedback"><?php echo $adrese_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Talrunis</label>
                            <input type="text" name="Talrunis" class="form-control <?php echo (!empty($talrunis_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($talrunis); ?>">
                            <span class="invalid-feedback"><?php echo $talrunis_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>"/>
                        <input type="submit" class="btn btn-primary" value="Saglabāt">
                        <a href="klienti.php" class="btn btn-secondary ml-2">Atcelt</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>