<?php include "../checkAutenfikacija.php";?>

<?php
// Проверка наличия ID
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    require_once "../config.php";

    $sql = "SELECT * FROM Tvertne WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = trim($_GET["id"]);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
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
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Tvertnes apskate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php";?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-4 mb-3">Tvertnes apskate</h1>
                    <div class="form-group">
                        <label>Nosaukums</label>
                        <p><b><?php echo htmlspecialchars($row["Nosaukums"]); ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Ūdens apjoms (L)</label>
                        <p><b><?php echo htmlspecialchars($row["UdensApjoms_L"]); ?></b></p>
                    </div>
                    <p><a href="tvertni.php" class="btn btn-primary">Atpakaļ</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
