<?php
require_once 'connection.php';

session_start();

if(isset($_COOKIE['username']) && !isset($_SESSION['username']))
{
    $username = $_COOKIE['username'];

    $session = $client->startSession();
    $session->startTransaction();

    try{
        $query = new MongoDB\Driver\Query(['username' => $username]);
        $cursor = $client->executeQuery("proiect.conturi", $query);

        $user = $cursor->toArray()[0] ?? null;

        if($user){
            $_SESSION['username'] = $username;
        }

        $session->commitTransaction();
    }catch(Exception $e){
        $session->abortTransaction();
    }

    $session->endSession();
}

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];  

    $session = $client->startSession();
    $session->startTransaction();

    try{
        $query = new MongoDB\Driver\Query(['username' => $username]);
        $cursor = $client->executeQuery("proiect.conturi", $query);

        $user = current($cursor->toArray());

        if($user){
            $pos = $user->user_type;
        }

        $session->commitTransaction();
    }catch(Exception $e){
        $session->abortTransaction();
    }

    $session->endSession();
}
?>