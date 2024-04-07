<?php   
require_once 'connection.php';

$status = 0;

if(isset($_POST['Register'])){
    $nume=$_POST['nume'];
    $pass=$_POST['pass'];
    $usertype='user'; 
    
    $filter = ['username' => $nume];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $client->executeQuery("proiect.conturi", $query);

    if(count($cursor->toArray()) > 0){
        $status = 1;
    }
    
    if($status==0){
        if($nume != null && $pass != null){
            
            $session = $client->startSession();
            $session->startTransaction(); 

            try{
                $bulk = new MongoDB\Driver\BulkWrite;
                $data = array(
                    '_id' => new MongoDB\BSON\ObjectID,
                    'username' => $nume,
                    'password' => $pass,
                    'user_type' => $usertype,
                );

                $bulk->insert($data);   

                $client->executeBulkWrite("proiect.conturi", $bulk);

                $session->commitTransaction();

                header('location:rememberme.php');
            }catch(Exception $e){
                $session->abortTransaction();
            }

            $session->endSession();  
        }
        else{ 
            header('location:registerform.php');
        }
    }
}
?>