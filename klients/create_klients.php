<?php include "../checkAutenfikacija.php";?>

<?php
require_once "../config.php";

$nosaukums = $adrese = $talrunis = "";
$nosaukums_err = $adrese_err = $talrunis_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $input_nosaukums = trim($_POST["Uznenuma_nosaukums"]);
    if(empty($input_nosaukums)){
        $nosaukums_err = "Lūdzu ievadiet nosaukums.";
    } else{
        $nosaukums = $input_nosaukums;
    }

    $input_adrese = trim($_POST["Adrese"]);
    if(empty($input_adrese)){
        $adrese_err = "Lūdzu ievadiet adresi.";
    } else{
        $adrese = $input_adrese;
    }

    $input_talrunis = trim($_POST["Talrunis"]);
    if (empty($input_talrunis)) {
        $talrunis_err = "Lūdzu, ievadiet tālruņa numuru.";
    } elseif (!preg_match("/^[0-9]{8,15}$/", $input_talrunis)) {
        $talrunis_err = "Lūdzu, ievadiet derīgu tālruņa numuru.";
    } else {
        $talrunis = $input_talrunis;
    }

    if(empty($nosaukums_err) && empty($adrese_err) && empty($talrunis_err)){
        $sql = "INSERT INTO Klients (Uznenuma_nosaukums, Adrese, Talrunis) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $nosaukums, $adrese, $talrunis);

            if(mysqli_stmt_execute($stmt)){
                header("location: klienti.php");
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
<?php include "../background.php"; ?>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Izveidot klientu Ierakstu</h2>
                <p>Lūdzu aizpildiet šo formu un aizsūtīt, lai pievienot ierakstu datubāzē.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <label>Tālrunis</label>
                        <input type="text" name="Talrunis" class="form-control <?php echo (!empty($talrunis_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($talrunis); ?>">
                        <span class="invalid-feedback"><?php echo $talrunis_err;?></span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Saglabāt">
                    <a href="klienti.php" class="btn btn-secondary ml-2">Atcelt</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
