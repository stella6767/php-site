<meta charset="utf-8">
<?php
    session_start();
    if (isset($_SESSION["userid"])) $userid = $_SESSION["userid"];
    else $userid = "";
    if (isset($_SESSION["username"])) $username = $_SESSION["username"];
    else $username = "";

    if ( !$userid )
    {  //여기서도 로그인 안 하면 거른다.
        echo("
                    <script>
                    alert('댓글달기는 로그인 후 이용해 주세요!');
                    history.go(-1)
                    </script>
        ");
                exit;
    }

	//$num  = $_GET["num"];
	$page = $_POST["page"]; //변수 값들을 받고 
    $num  = $_POST["num"];
  	$comment = $_POST["comment"];
  	//$comment = $_GET['comment'];
	$comment = htmlspecialchars($comment, ENT_QUOTES);

	//$total = $_POST["total"];
	//$total = $total+1;


	$regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장


	
	$con = mysqli_connect("localhost", "stella", "stella@6767", "php연습");

	$sql = "insert into comment (id, name, comment, regist_day,sel) values ('$userid', '$username', '$comment', '$regist_day','$num') "; //차례대로 넣어주자. num은 게시글에 종속된 댓글을 보여주기 위해 꼭 필요함
	mysqli_query($con, $sql);  // $sql 에 저장된 명령 실행

	// 댓글 달면 포인트 부여하기
  	$point_up = 10;

	$sql = "select point from members where id='$userid'";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$new_point = $row["point"] + $point_up;
	
	$sql = "update members set point=$new_point where id='$userid'";
	mysqli_query($con, $sql);

	$sql = "select * from board where num=$num";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);

	$new_total          = $row["total"] + 1;  //댓글 수도 1씩 증가하도록
	$sql = "update board set total=$new_total where num=$num ";
	mysqli_query($con, $sql); 

	mysqli_close($con);                // DB 연결 끊기



//다시 board_view에서 가져온 변수를 고대로 board_view로 전달하며 이동 이래야 오류가 안남. 
	echo "
	   <script>
		location.href='board_view.php?num=$num&page=$page'; 
	   </script>
	";
?>
 