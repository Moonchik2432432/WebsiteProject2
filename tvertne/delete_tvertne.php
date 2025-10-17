<?php include "../checkAutenfikacija.php";?>

<?php
$err = "";

// Apstrāde, kad forma tiek iesniegta
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && !empty($_POST["id"])) {
    require_once "../config.php";

    $param_id = trim($_POST["id"]);

    $check_sql = "SELECT COUNT(*) FROM Piegade WHERE Tvertne_ID = ?";
    if ($check_stmt = mysqli_prepare($link, $check_sql)) {
        mysqli_stmt_bind_param($check_stmt, "i", $param_id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_bind_result($check_stmt, $count);
        mysqli_stmt_fetch($check_stmt);
        mysqli_stmt_close($check_stmt);

        if ($count > 0) {
            // Ieraksts tiek izmantots – nevar dzēst
            $err = "Nevar dzēst: šis tvertne tiek izmantots tabulā 'Piegade'.";
        } else {
            // Nav atkarību – var dzēst
            $sql = "DELETE FROM Tvertne WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $param_id);
                if (mysqli_stmt_execute($stmt)) {
                    // Pāradresēt pēc veiksmīgas dzēšanas
                    header("location: tvertni.php");
                    exit();
                } else {
                    $err = "Radās kļūda dzēšot ierakstu. Lūdzu, mēģiniet vēlreiz.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }

    mysqli_close($link);

} else {
    // Ja 'id' nav padots GET pieprasījumā – pāradresēt
    if (empty(trim($_GET["id"]))) {
        header("location: ../error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Dzēst klientu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php"; ?>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4 mb-3">Dzēst tvertnu</h2>

                <?php if (!empty($err)): ?>
                    <div class="alert alert-warning"><?php echo $err; ?></div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger">
                        <input type="hidden" name="id" value="<?php echo isset($_GET["id"]) ? htmlspecialchars(trim($_GET["id"])) : ''; ?>"/>
                        <p>Vai tiešām vēlaties dzēst šo tvertnu ierakstu?</p>
                        <p>
                            <input type="submit" value="Ja" class="btn btn-danger">
                            <a href="tvertni.php" class="btn btn-secondary">Nē</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
