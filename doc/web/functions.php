<?php

function connect_db(){
  $param ='mysql:dbname=works;host=localhost:3307';
  $pdo = new PDO($param,'root','root');
  $pdo->query('SET NAMES utf8;');
  return $pdo;
}



function time_format_dw($date){
  $format_date = NULL;
  $week = array('日','月','火','水','木','金','土');

  if($date){
    $format_date = date('j('.$week[date('w',strtotime($date))].')',strtotime($date));
  }

 return $format_date;
}
?>