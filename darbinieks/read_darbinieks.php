<?php include "../checkAutenfikacija.php";?>

<?php
// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Include config file
    require_once "../config.php";

    // Prepare a select statement with join to Amats
    $sql = "SELECT d.*, a.Nosaukums AS Amats_Nosaukums FROM Darbinieks d LEFT JOIN Amats a ON d.Amats_ID = a.id WHERE d.id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = trim($_GET["id"]);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else {
                header("location:../error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
} else {
    header("location:../error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Darbinieka apskate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php";?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-4 mb-3">Darbinieka apskate</h1>
                    <div class="form-group">
                        <label>Vārds</label>
                        <p><b><?php echo htmlspecialchars($row["Vards"]); ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Uzvārds</label>
                        <p><b><?php echo htmlspecialchars($row["Uzvards"]); ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Amats</label>
                        <p><b><?php echo htmlspecialchars($row["Amats_Nosaukums"] ?? ''); ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Tālrunis</label>
                        <p><b><?php echo htmlspecialchars($row["Talrunis"]); ?></b></p>
                    </div>
                    <p><a href="darbinieki.php" class="btn btn-primary">Atpakaļ</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>