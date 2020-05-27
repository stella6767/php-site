<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
<title>PHP 개발자 입문일기</title>
<link rel="stylesheet" type="text/css" href="./css/board.css"> <!-- css 적용이 안 되어 바꿈 -->
<link rel="stylesheet" type="text/css" href="./css/common.css">
</head>
<body> 
<header>
    <?php include "header.php";?>
</header>  
<section class="siba">
	<div id="main_img_bar">
        <img src="./img/main_img.png">
    </div>
   	<div id="board_box">
	    <h3 class="title">
			
<?php
	$num  = $_GET["num"];
	$page  = $_GET["page"];

	$con = mysqli_connect("localhost", "stella", "stella@6767", "php연습");
	$sql = "select * from board where num=$num";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	//$row = mysqli_query($con, $sql);

	$row = mysqli_fetch_array($result);
	$id      = $row["id"];
	$name      = $row["name"];
	$regist_day = $row["regist_day"];
	$subject    = $row["subject"];
	$content    = $row["content"];
	$file_name    = $row["file_name"];
	$file_type    = $row["file_type"];
	$file_copied  = $row["file_copied"];
	$hit          = $row["hit"];
	$category     = $row["catagory"];

	if($category==1) //추가한 거 위의 h3 과 연결
		echo "리뷰 게시판 > 목록 보기";
	else
		echo "건의 게시판 > 목록 보기";



	$content = str_replace(" ", "&nbsp;", $content);
	$content = str_replace("\n", "<br>", $content);

	$new_hit = $hit + 1;
	$sql = "update board set hit=$new_hit where num=$num";   
	mysqli_query($con, $sql);
?>		
		</h3>
	    <ul id="view_content">
			<li>
				<span class="col1"><b>제목 :</b> <?=$subject?></span>
				<span class="col2"><?=$name?> | <?=$regist_day?></span>
			</li>
			<li>
				<?php
					if($file_name) {
						$real_name = $file_copied;
						$file_path = "./data/".$real_name;
						$file_size = filesize($file_path);

						echo "▷ 첨부파일 : $file_name ($file_size Byte) &nbsp;&nbsp;&nbsp;&nbsp;
			       		<a href='download.php?num=$num&real_name=$real_name&file_name=$file_name&file_type=$file_type'>[저장]</a><br><br>";
			           	}
				?>
				<?=$content?>
			</li>	
	    </ul>
			
	    <ul class="buttons">
				<li><button onclick="location.href='board_list.php?page=<?=$page?>'">목록</button></li>
				<li><button onclick="location.href='board_modify_form.php?num=<?=$num?>&page=<?=$page?>'">수정</button></li>
				<li><button onclick="location.href='board_delete.php?num=<?=$num?>&page=<?=$page?>'">삭제</button></li>
				<li><button onclick="location.href='board_form.php'">글쓰기</button></li>
		</ul>
<ul id="list">
			<li></li></ul>
<?php
   
   mysqli_close($con); //위에 연결한 거 끊어주고 

	$con = mysqli_connect("localhost", "stella", "stella@6767", "php연습");
	$sql = "select * from comment where sel=$num order by regist_day"; //댓글 테이블 연결 등록일 순대로
	$result = mysqli_query($con, $sql);
  
	$total_record = mysqli_num_rows($result); //전체 글

   for ($i=0; $i<$total_record; $i++) //끝까지 다 출력한다.
   {
      mysqli_data_seek($result, $i);
      // 가져올 레코드로 위치(포인터) 이동
      $row = mysqli_fetch_array($result);
      // 하나의 레코드 가져오기
	  $id          = $row["id"];
	  $name        = $row["name"];
	  $regist_day = $row["regist_day"];
	  $comment    = $row["comment"];
	

?> 
      <ul id="comment_list">
			<li>
					<span class="co1"><?=$name?></span>
					<span class="co2"><?=$comment?></span>
					<span class="co3"><?=$regist_day?></span>
				</li>	
<?php
   }
   mysqli_close($con);
  
?>
</ul>
	<!-- 댓글창 입력 form문 -->
		<form method="post" name="comment_form" id="comment_form"  action="comment_insert.php" > 
             <ul id="comment_form">
                	<li id="text_area">	
	    			<span class="col1"><?=$username?> : </span>
	    			<span class="col2">
	    				<textarea name="comment"></textarea>
	    			</span>   
            		</li>
            </ul>
            	<input type="hidden" name="page" value="<?php echo $page; ?>" />
            	<input type="hidden" name="num" value="<?php echo $num; ?>" />            	
            	
            	<!-- a링크와 form 문 동시에는 안되는듯 -->
            <ul class="buttons">
				<li><button onclick="location.href='board_list.php?page=<?=$page?>'">목록</button></li>
                <li>
				 <button type="button" onclick="check_input()">댓글쓰기</button></li>
            </ul>
        </form>
      



	</div> <!-- board_box -->
</section> 

<footer>
    <?php include "footer.php";?>
</footer>

<script> //댓글 보내는 함수, 걸러줄 건 걸러주자
  function check_input() {
      if (!document.comment_form.comment.value)
      {
          alert("댓글내용을 입력하세요!");    
          document.comment_form.comment.focus();
          return;
      }
      document.comment_form.submit();
   }
</script>
<!-- <script>
    function search1(){
        if(comment_form.comment.value){
            comment_form.submit();
        }else{
            location.href="board_view.php";
        }
    }
</script> -->


</body>
</html>
