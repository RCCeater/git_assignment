<?php

include $_SERVER['DOCUMENT_ROOT']."/study/assignment2/db.php";

//각 변수에 write.php에서 input name값들을 저장한다
$username = $_POST['name'];
$title = $_POST['title'];
$content = $_POST['content'];
$date = date('Y-m-d');

$mqq = mq("alter table board auto_increment =1"); //auto_increment 값 초기화

if($username && $title && $content){
    $sql = mq("insert into board(name,title,content,date) values('".$username."','".$title."','".$content."','".$date."')"); 
    echo "<script>
    alert('글쓰기 완료되었습니다.');
    location.href='./';</script>";
}else{
    echo "<script>
    alert('글쓰기에 실패했습니다.');
    history.back();</script>";
}
?>

