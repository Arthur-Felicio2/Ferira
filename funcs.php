<?php

function conecta($params = "") 
 { 
     $params = "pgsql:host=localhost; port=5432; dbname=Feira; user=postgres; password=postgres"; 
     try { 
         $varConn = new PDO($params); 
         return $varConn; 
     } catch (PDOException $e) { 
         echo "não conectou"; 
         echo $e->getMessage(); 
         exit; 
     } 
 };