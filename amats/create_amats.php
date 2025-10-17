<?php
require_once "../config.php";

$nosaukums = "";
$nosaukums_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $input_nosaukums = trim($_POST["Nosaukums"]);
    if(empty($input_nosaukums)){
        $nosaukums_err = "Lūdzu ievadiet nosaukums amatu.";
    } else{
        $nosaukums = $input_nosaukums;
    }

    if(empty($nosaukums_err)){
        // Sagatavots vaicājums, lai izvairītos no SQL injekcijas
        $sql = "INSERT INTO Amats (Nosaukums) VALUES (?)";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $nosaukums);

            if(mysqli_stmt_execute($stmt)){
                header("location: amati.php");
                exit();
            } else{
                echo "Kļūda vaicājuma izpildē: " . mysqli_error($link);
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
    <title>Izveidot Ierakstu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include "../background.php";?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4">Izveidot Amatu Ierakstu</h2>
                    <p>Lūdzu aizpildiet šo formu un aizsūtīt, lai pievienot ierakstu datubāzē.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nosaukums</label>
                            <input type="text" name="Nosaukums" class="form-control <?php echo (!empty($nosaukums_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($nosaukums); ?>">
                            <span class="invalid-feedback"><?php echo $nosaukums_err;?></span>
                        </div>                   
                        <input type="submit" class="btn btn-primary" value="Saglabāt">
                        <a href="amati.php" class="btn btn-secondary ml-2">Atcelt</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
