# 게시판 기능에 대한 아이디어

- **게시판에 필요한 항목 생성**(게시판 번호, 제목, 글쓴이, 작성일, 조회수 등)
- **게시글 작성 기능**
- **목록으로 돌아가기, 수정, 삭제 기능**

---

# 게시판의 기능이 포함된 소스코드

### 1. DB 테이블

```python
<?php

	header('Content-Type: text/html; charset=utf-8'); // utf-8인코딩

	$db = new mysqli("localhost","root","1234","bbs");
	$db->set_charset("utf8");

	function mq($sql)
	{
		global $db;
		return $db->query($sql);
	}
?>
```

### 2. 게시판 페이지

```python
<?php include  $_SERVER['DOCUMENT_ROOT']."/study/assignment2/db.php"; ?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="/study/assignment2/style.css" />
</head>
<body>
<div id="board_area">
  <h1>자유게시판</h1>
  <h4>자유롭게 글을 쓸 수 있는 게시판입니다.</h4>
    <table class="list-table">
      <thead>
          <tr>
              <th width="70">번호</th>
                <th width="500">제목</th>
                <th width="120">글쓴이</th>
                <th width="100">작성일</th>
                <th width="100">조회수</th>
            </tr>
        </thead>
        <?php
        // board테이블에서 idx를 기준으로 내림차순해서 5개까지 표시
          $sql = mq("select * from board order by idx desc limit 0,5");
            while($board = $sql->fetch_array())
            {
              //title변수에 DB에서 가져온 title을 선택
              $title=$board["title"];
              if(strlen($title)>30)
              {
                //title이 30을 넘어서면 ...표시
                $title=str_replace($board["title"],mb_substr($board["title"],0,30,"utf-8")."...",$board["title"]);
              }
        ?>
      <tbody>
        <tr>
          <td width="70"><?php echo $board['idx']; ?></td>
          <td width="500"><a href="./read.php?idx=<?php echo $board["idx"];?>"><?php echo $title;?></a></td>
          <td width="120"><?php echo $board['name']?></td>
          <td width="100"><?php echo $board['date']?></td>
          <td width="100"><?php echo $board['hit']; ?></td>
        </tr>
      </tbody>
      <?php } ?>
    </table>
    <div id="write_btn">
      <a href="./write.php"><button>글쓰기</button></a>
    </div>
  </div>
</body>
</html>
```

#### 2-1 게시판 페이지의 CSS

```
@charset "utf-8";

/* 전체 옵션 */
* {
  margin: 0 auto;
  padding: 0;
  font-family: 'Malgun gothic', 'Sans-Serif', 'Arial';
}
a {
  text-decoration: none;
  color: #333;
}
ul li {
  list-style: none;
}

/* 공통 */
.fl {
  float: left;
}
.tc {
  text-align: center;
}

/* 게시판 목록 */
#board_area {
  width: 900px;
  position: relative;
}
.list-table {
  margin-top: 40px;
}
.list-table thead th {
  height: 40px;
  border-top: 2px solid #09c;
  border-bottom: 1px solid #ccc;
  font-weight: bold;
  font-size: 17px;
}
.list-table tbody td {
  text-align: center;
  padding: 10px 0;
  border-bottom: 1px solid #ccc;
  height: 20px;
  font-size: 14px;
}
#write_btn {
  position: absolute;
  margin-top: 20px;
  right: 0;
}

```

### 3. 게시글 작성 부분

```
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="/study/assignment2/style2.css" />
</head>
<body>
    <div id="board_write">
        <h1><a href="/">자유게시판</a></h1>
        <h4>글을 작성하는 공간입니다.</h4>
            <div id="write_area">
                <form action="write_ok.php" method="post">
                    <div id="in_title">
                        <textarea name="title" id="utitle" rows="1" cols="55" placeholder="제목" maxlength="100" required></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_name">
                        <textarea name="name" id="uname" rows="1" cols="55" placeholder="글쓴이" maxlength="100" required></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_content">
                        <textarea name="content" id="ucontent" placeholder="내용" required></textarea>
                    </div>
                    <div class="bt_se">
                        <button type="submit">글 작성</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
```

#### 3-1 게시한 글을 DB에 담기

```
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
```

#### 3-2 게시글 작성 부분의 CSS

```
/* 게시판 글쓰기 */
#board_write {
  width: 900px;
  position: relative;
  margin: 0 auto;
}
#write_area {
  margin-top: 70px;
  font-size: 14px;
}
#in_name {
  margin-top: 30px;
}
#in_name textarea {
  font-weight: bold;
  font-size: 26px;
  color: #333;
  width: 900px;
  border: none;
  resize: none;
}
#in_title {
  margin-top: 30px;
}
#in_title textarea {
  font-weight: bold;
  font-size: 26px;
  color: #333;
  width: 900px;
  border: none;
  resize: none;
}
.wi_line {
  border: solid 1px lightgray;
  margin-top: 10px;
}
#in_content {
  margin-top: 10px;
}
#in_content textarea {
  font: 14px;
  color: #333;
  width: 900px;
  height: 400px;
  resize: none;
}
.bt_se {
  margin-top: 20px;
  text-align: center;
}
.bt_se button {
  width: 100px;
  height: 30px;
}
```

### 4. 게시글 읽기

```
<?php
	include $_SERVER['DOCUMENT_ROOT']."/study/assignment2/db.php"; /* db load */
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="/study/assignment2/style3.css" />
</head>
<body>
	<?php
		$bno = $_GET['idx']; /* bno함수에 idx값을 받아와 넣음*/
		$hit = mysqli_fetch_array(mq("select * from board where idx ='".$bno."'"));
		$hit = $hit['hit'] + 1;
		$fet = mq("update board set hit = '".$hit."' where idx = '".$bno."'");
		$sql = mq("select * from board where idx='".$bno."'"); /* 받아온 idx값을 선택 */
		$board = $sql->fetch_array();
	?>
<!-- 글 불러오기 -->
<div id="board_read">
	<h2><?php echo $board['title']; ?></h2>
		<div id="user_info">
			<?php echo $board['name']; ?> <?php echo $board['date']; ?> 조회:<?php echo $board['hit']; ?>
				<div id="bo_line"></div>
			</div>
			<div id="bo_content">
				<?php echo nl2br("$board[content]"); ?>
			</div>
	<!-- 목록, 수정, 삭제 -->
	<div id="bo_ser">
		<ul>
			<li><a href="./">[목록으로]</a></li>
			<li><a href="modify.php?idx=<?php echo $board['idx']; ?>">[수정]</a></li>
			<li><a href="delete.php?idx=<?php echo $board['idx']; ?>">[삭제]</a></li>
		</ul>
	</div>
</div>
</body>
</html>
```

#### 4-1 게시글 읽기 부분의 CSS

```
/* 게시판 read */
#board_read {
  width: 900px;
  position: relative;
  word-break: break-all;
}
#user_info {
  font-size: 14px;
}
#user_info ul li {
  float: left;
  margin-left: 10px;
}
#bo_line {
  width: 880px;
  height: 2px;
  background: gray;
  margin-top: 20px;
}
#bo_content {
  margin-top: 20px;
}
#bo_ser {
  font-size: 14px;
  color: #333;
  position: absolute;
  right: 0;
}
#bo_ser > ul > li {
  float: left;
  margin-left: 10px;
}
```

### 5. 게시글 수정

```
<!--- 게시글 수정 -->
<?php
	include $_SERVER['DOCUMENT_ROOT']."/study/assignment2/db.php";

	$bno = $_GET['idx'];
	$sql = mq("select * from board where idx='$bno';");
	$board = $sql->fetch_array();
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" href="/study/assignment2/style2.css" />
</head>
<body>
    <div id="board_write">
        <h1><a href="/">자유게시판</a></h1>
        <h4>글을 수정합니다.</h4>
            <div id="write_area">
                <form action="modify_ok.php?idx=<?php echo $bno; ?>" method="post">
                    <div id="in_title">
                        <textarea name="title" id="utitle" rows="1" cols="55" placeholder="제목" maxlength="100" required><?php echo $board['title']; ?></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_name">
                        <textarea name="name" id="uname" rows="1" cols="55" placeholder="글쓴이" maxlength="100" required><?php echo $board['name']; ?></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_content">
                        <textarea name="content" id="ucontent" placeholder="내용" required><?php echo $board['content']; ?></textarea>
                    </div>
                    <div class="bt_se">
                        <button type="submit">글 작성</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
```

#### 5-1 수정한 글을 DB에 담기

```
<?php
include $_SERVER['DOCUMENT_ROOT']."/study/assignment2/db.php";

$bno = $_GET['idx'];
$username = $_POST['name'];
$title = $_POST['title'];
$content = $_POST['content'];
$sql = mq("update board set name='".$username."',title='".$title."',content='".$content."' where idx='".$bno."'"); ?>

<script type="text/javascript">alert("수정되었습니다."); </script>
<meta http-equiv="refresh" content="0 url=./read.php?idx=<?php echo $bno; ?>">
```

#### 5-2 수정 부분의 CSS

**게시글 작성의 CSS와 동일**

### 6. 게시글 삭제

```
<?php
	include $_SERVER['DOCUMENT_ROOT']."/study/assignment2/db.php";

	$bno = $_GET['idx'];
	$sql = mq("delete from board where idx='$bno';");
?>
<script type="text/javascript">alert("삭제되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=./" />
```

### 7. 기타

**깃 주소** [here](https://github.com/RCCeater/git_assignment)

---

**정상작동 스크린샷**

**git repository**에 올라온 파일을 봐주세요
