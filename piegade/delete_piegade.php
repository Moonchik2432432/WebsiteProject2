<?php include "../checkAutenfikacija.php";?>

<?php
require_once "../config.php";

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $sql = "DELETE FROM Piegade WHERE id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        $param_id = (int)trim($_POST["id"]);
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        if(mysqli_stmt_execute($stmt)){
            header("location: piegade.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Kļūda sagatavojot vaicājumu: " . mysqli_error($link);
    }

    mysqli_close($link);
} else {
    if(empty(trim($_GET["id"]))){
        header("location: ../error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Dzēst ierakstu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php";?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4 mb-3">Dzēst piegadataju</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars(trim($_GET["id"])); ?>"/>
                            <p>Vai tiešām vēlaties dzēst šo piegadataju ierakstu?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="piegade.php" class="btn btn-secondary">Ne</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
