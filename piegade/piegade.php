<?php include "../checkAutenfikacija.php";?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Piegādes saraksts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../styles.css">
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <?php include "../navigation.php";?>
    <?php include "../background.php";?>
    <div class="wrapper">
        <div class="container-table">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Piegādes saraksts</h2>
                        <a href="create_piegade.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Pievienot piegādi</a>
                    </div>
                    <?php
                    require_once "../config.php";
                    //Берет данные из другой таблицы

                    $sqlPiegadatajs = mysqli_query($link, 'SELECT `id`, `Uznenuma_nosaukums` FROM `Klients`');
                    $piegadatajsMap = [];
                    while ($resultPiegadatajs = mysqli_fetch_assoc($sqlPiegadatajs)) {
                        $piegadatajsMap[$resultPiegadatajs['id']] = $resultPiegadatajs['Uznenuma_nosaukums'];
                    }

                    $sqlPasutijuma = mysqli_query($link, 'SELECT `id`, `Vards`, `Uzvards` FROM `Darbinieks`');
                    $pasutijumaMap = [];
                    while ($resultPasutijuma = mysqli_fetch_assoc($sqlPasutijuma)) {
                        $pasutijumaMap[$resultPasutijuma['id']] = $resultPasutijuma['Vards'] . ' ' . $resultPasutijuma['Uzvards'];
                    }

                    $sqlTvertne = mysqli_query($link, 'SELECT `id`, `Nosaukums` FROM `Tvertne`');
                    $tvertneMap = [];
                    while ($resultTvertne = mysqli_fetch_assoc($sqlTvertne)) {
                        $tvertneMap[$resultTvertne['id']] = $resultTvertne['Nosaukums'];
                    }

                    //
                    $sql = "SELECT * FROM Piegade";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Piegādes datums</th>";
                                        echo "<th>Cena(€)</th>";
                                        echo "<th>Daudzums</th>";
                                        echo "<th>Klients</th>";
                                        echo "<th>Piegades darbinieks</th>";
                                        echo "<th>Tvertne</th>";
                                        echo "<th>Darbības</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Piegades_datums']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Piegade_summa']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Daudzums_tvertni']) . "</td>";

                                        //Проверяет данные и подставляет за вместо ид, данными из других таблицы
                                        $piegadatajsName = isset($piegadatajsMap[$row['Klients_ID']]) ? htmlspecialchars($piegadatajsMap[$row['Klients_ID']]) : 'N/A';
                                        echo "<td>" . $piegadatajsName . "</td>";

                                        $pasutijumaName = isset($pasutijumaMap[$row['Piegades_darbinieks_ID']]) ? htmlspecialchars($pasutijumaMap[$row['Piegades_darbinieks_ID']]) : 'N/A';
                                        echo "<td>" . $pasutijumaName . "</td>";

                                        $tvertneName = isset($tvertneMap[$row['Tvertne_ID']]) ? htmlspecialchars($tvertneMap[$row['Tvertne_ID']]) : 'N/A';
                                        echo "<td>" . $tvertneName . "</td>";
                                        //
                                        echo "<td>";
                                            echo '<a href="read_piegade.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update_piegade.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete_piegade.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>Nav piegāžu.</em></div>';
                        }
                    } else{
                        echo "Oops! Kaut kas nogāja greizi. Mēģiniet vēlreiz vēlāk.";
                    }
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>