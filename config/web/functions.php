<?php



function connect_db(){
  $param ='mysql:dbname=works;host=localhost:3307';
  $pdo = new PDO($param,'root','root');
  $pdo->query('SET NAMES utf8;');
  return $pdo;
}
?>