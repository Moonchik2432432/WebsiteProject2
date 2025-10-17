<?php include "../checkAutenfikacija.php";?>

<?php
require_once "../config.php";

// Инициализация переменных
$vards = $uzvards = $amats_id = $talrunis = "";
$vards_err = $uzvards_err = $amats_id_err = $talrunis_err = "";

// Получение списка должностей для выпадающего списка
$amati = [];
$amati_sql = "SELECT id, Nosaukums FROM Amats";
if ($amati_stmt = mysqli_prepare($link, $amati_sql)) {
    mysqli_stmt_execute($amati_stmt);
    $amati_result = mysqli_stmt_get_result($amati_stmt);
    while ($row = mysqli_fetch_assoc($amati_result)) {
        $amati[$row["id"]] = $row["Nosaukums"];
    }
    mysqli_stmt_close($amati_stmt);
}

// Обработка отправки формы
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $id = $_POST["id"];

    // Валидация
    $input_vards = trim($_POST["Vards"]);
    if (empty($input_vards)) {
        $vards_err = "Lūdzu ievadiet vārdu.";
    } else {
        $vards = $input_vards;
    }

    $input_uzvards = trim($_POST["Uzvards"]);
    if (empty($input_uzvards)) {
        $uzvards_err = "Lūdzu ievadiet uzvārdu.";
    } else {
        $uzvards = $input_uzvards;
    }

    $input_amats_id = trim($_POST["Amats_ID"]);
    if (empty($input_amats_id) || !isset($amati[$input_amats_id])) {
        $amats_id_err = "Izvēlieties amatu.";
    } else {
        $amats_id = $input_amats_id;
    }

    $input_talrunis = trim($_POST["Talrunis"]);
    if (!empty($input_talrunis) && !preg_match('/^[0-9+\-\s]+$/', $input_talrunis)) {
        $talrunis_err = "Ievadiet korektu tālruņa numuru.";
    } else {
        $talrunis = $input_talrunis;
    }


    if (empty($vards_err) && empty($uzvards_err) && empty($amats_id_err) && empty($talrunis_err)) {
        $sql = "UPDATE Darbinieks SET Vards=?, Uzvards=?, Amats_ID=?, Talrunis=? WHERE id=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssisi", $vards, $uzvards, $amats_id, $talrunis, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("location: darbinieki.php");
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
        $sql = "SELECT * FROM Darbinieks WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $vards = $row["Vards"];
                    $uzvards = $row["Uzvards"];
                    $amats_id = $row["Amats_ID"];
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
                    <h2 class="mt-4 mb-3">Labot darbinieku</h2>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Vārds</label>
                            <input type="text" name="Vards" class="form-control <?php echo (!empty($vards_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($vards); ?>">
                            <span class="invalid-feedback"><?php echo $vards_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Uzvārds</label>
                            <input type="text" name="Uzvards" class="form-control <?php echo (!empty($uzvards_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($uzvards); ?>">
                            <span class="invalid-feedback"><?php echo $uzvards_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Amats</label>
                            <select name="Amats_ID" class="form-control <?php echo (!empty($amats_id_err)) ? 'is-invalid' : ''; ?>">
                                <option value="">Izvēlieties amatu</option>
                                <?php foreach ($amati as $aid => $nos): ?>
                                    <option value="<?php echo $aid; ?>" <?php echo ($amats_id == $aid) ? "selected" : ""; ?>>
                                        <?php echo htmlspecialchars($nos); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $amats_id_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Tālrunis</label>
                            <input type="text" name="Talrunis" class="form-control <?php echo (!empty($talrunis_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($talrunis); ?>">
                            <span class="invalid-feedback"><?php echo $talrunis_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>"/>
                        <input type="submit" class="btn btn-primary" value="Saglabāt">
                        <a href="darbinieki.php" class="btn btn-secondary ml-2">Atcelt</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>