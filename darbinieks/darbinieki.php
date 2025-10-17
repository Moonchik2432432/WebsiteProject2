<?php include "../checkAutenfikacija.php";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../styles.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
                        <h2 class="pull-left">Darbinieku saraksts</h2>
                        <a href="create_darbinieks.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Izveidot jaunu darbinieku</a>
                    </div>
                    <?php
                    require_once "../config.php";
                    
                    // Получаем должности в справочник
                    $sqlAmats = mysqli_query($link, 'SELECT `id`, `Nosaukums` FROM `Amats`');
                    $amatsMap = [];
                    while ($resultAmats = mysqli_fetch_assoc($sqlAmats)) {
                        $amatsMap[$resultAmats['id']] = $resultAmats['Nosaukums'];
                    }

                    $sql = "SELECT * FROM Darbinieks";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Vārds</th>";
                                        echo "<th>Uzvārds</th>";
                                        echo "<th>Amats</th>";
                                        echo "<th>Tālrunis</th>";
                                        echo "<th>Darbības</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Vards']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Uzvards']) . "</td>";
                                        $amatsName = isset($amatsMap[$row['Amats_ID']]) ? htmlspecialchars($amatsMap[$row['Amats_ID']]) : 'N/A';
                                        echo "<td>" . $amatsName . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Talrunis']) . "</td>";
                                        echo "<td>";
                                            echo '<a href="read_darbinieks.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update_darbinieks.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete_darbinieks.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>