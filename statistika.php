<?php
require_once "config.php";

$views = [
    "Kopējā_piegādes_summa" => "Kopējā piegādes summa",
    "Aktīvākais_piegādes_darbinieks" => "Aktīvākais piegādes darbinieks",
    "Piegāžu_skaits_pa_mēnešiem" => "Piegāžu skaits pa mēnešiem",
    "Maksimālā_un_minimālā_piegādes_summa" => "Maksimālā un minimālā piegādes summa"
];
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Statistika no skatiem (VIEW)</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <?php include "checkAutenfikacija.php";?>
    <?php include "background.php"; ?>
    <?php include "navigation.php";?>
    
    <div class="wrapper container-fluid">
        <h2>Statistika</h2>

        <?php foreach ($views as $view_name => $view_title): ?>
            <h3><?php echo htmlspecialchars($view_title); ?></h3>

            <?php
            $query = "SELECT * FROM `$view_name`";
            $result = $link->query($query);

            if (!$result) {
                echo "<p class='alert alert-danger'>Kļūda vaicājumā: " . htmlspecialchars($link->error) . "</p>";
                continue;
            }

            if ($result->num_rows > 0):
            ?>
                <div class="container-table">
                    <table class="table table-striped table-bordered table-dark" style="width: 600px;">
                        <thead>
                            <tr>
                                <?php
                                $fields = $result->fetch_fields();
                                foreach ($fields as $field) {
                                    echo "<th>" . htmlspecialchars($field->name) . "</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <?php
                                    foreach ($fields as $field) {
                                        $cell = $row[$field->name];
                                        echo "<td>" . htmlspecialchars($cell) . "</td>";
                                    }
                                    ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>Datu nav.</p>
            <?php endif; ?>

            <br>
        <?php endforeach; ?>
    </div>
</body>
</html>
