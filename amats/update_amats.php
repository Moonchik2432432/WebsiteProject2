<?php
require_once "../config.php";

// Инициализация переменных
$nosaukums = "";
$nosaukums_err = "";

// Обработка отправки формы
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $id = $_POST["id"];

    // Валидация
    $input_nosaukums = trim($_POST["Nosaukums"]);
    if (empty($input_nosaukums)) {
        $nosaukums_err = "Lūdzu ievadiet vārdu.";
    } else {
        $nosaukums = $input_nosaukums;
    }

    if (empty($nosaukums_err)) {
        $sql = "UPDATE Amats SET Nosaukums=? WHERE id=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $nosaukums, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("location: amati.php");
                exit();
            } else {
                echo "Kļūda saglabājot datus.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
} else {
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id = trim($_GET["id"]);
        $sql = "SELECT * FROM Amats WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $nosaukums = $row["Nosaukums"];
                } else {
                    header("location: ../error.php");
                    exit();
                }
            } else {
                echo "Kļūda vaicājumā.";
            }
            mysqli_stmt_close($stmt);
        }
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
    <title>Labot darbinieku</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php";?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4 mb-3">Labot amatu</h2>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Nosaukums</label>
                            <input type="text" name="Nosaukums" class="form-control <?php echo (!empty($nosaukums_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($nosaukums); ?>">
                            <span class="invalid-feedback"><?php echo $nosaukums_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>"/>
                        <input type="submit" class="btn btn-primary" value="Saglabāt">
                        <a href="amati.php" class="btn btn-secondary ml-2">Atcelt</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>