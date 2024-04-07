<?php
require_once "connection.php"; 
 
$bulk = new MongoDB\Driver\BulkWrite;

if(isset($_POST['upload'])){
    if(!empty($_POST['nume']) && !empty($_POST['ingrediente']) && !empty($_POST['tipbautura']) && !empty($_POST['pret']) && !empty($_FILES['image']['name'])) {
        $text=$_POST['nume'];
        $target="./multimedia/".md5(uniqid(time())).basename($_FILES['image']['name']);
        $ingrediente=$_POST['ingrediente'];
        $tipb=$_POST['tipbautura'];
        $pret=$_POST['pret'];

        $data = array(
            '_id' => new MongoDB\BSON\ObjectID,
            'nume' => $text,
            'imagine' => $target,
            'ingrediente' => $ingrediente,
            'tip_bautura' => $tipb,
            'pret' => $pret,
        );

        $bulk->insert($data);

        $client->executeBulkWrite("proiect.bauturi", $bulk);
 
        if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
            //header('location:administrarebaza.php');
            echo "<script>alert('Băutura a fost încărcată cu succes!'); 
                    window.location.href = 'administrarebaza.php';</script>";
        }
        else{ 
            echo "<script>alert('Eroare la incarcarea imaginii.');
                    window.location.href = 'upload.php';</script>";
        } 
    }else { 
        echo "<script>alert('Toate câmpurile sunt obligatorii!');
              window.location.href = 'upload.php';</script>";
    }
}   