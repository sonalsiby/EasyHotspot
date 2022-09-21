<?php
    if ( !isset($_SESSION) ) session_start();
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header('Location: logout.php');
    }
    if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
        header('Location: logout.php');
    }

    $data = $_POST['data'];
    $ejson = json_decode($data, true);
    $dataArray = $ejson['data'];
?>

<table>
    <thead>
        <?php 
            foreach ($dataArray[0] as $key => $value) {
                echo '<th>'.ucwords(str_replace('_', ' ', $key)).'</th>';
            }
        ?>
    </thead>
    <tbody>
        <?php
            for ($i=0; $i<count($dataArray); $i++) {
                echo '<tr>';
                foreach ($dataArray[$i] as $key => $value) {
                    echo '<td>'.$value.'</td>';
                }
                echo '</tr>';
            } 
        ?>
    </tbody>
    <tfoot>
        
    </tfoot>
</table>