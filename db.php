<?php

$db = mysqli_connect('localhost','root','1234','level1');

if($db)
{
  echo 'DB 접속 실패';
}
else
{
  echo 'DB 접속 성공';
}














?>