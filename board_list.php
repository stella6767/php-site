<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
<title>본격 편의점 리뷰</title>
<link rel="stylesheet" type="text/css" href="./css/common.css">
<link rel="stylesheet" type="text/css" href="./css/board.css">
</head>
<body> 
<header>
    <?php include "header.php";?>
</header>  
<section>
	<div id="main_img_bar">
         <img src="./img/main_img.png">
    </div>
   	<div id="board_box">
	    <h3>
	    	리뷰 게시판 > 목록보기
		</h3>



	    <ul id="board_list">
				<li>
					<span class="col1">번호</span>
					<span class="col2">제목</span>
					<span class="col3">글쓴이</span>
					<span class="col4">첨부</span>
					<span class="col5">등록일</span>
					<span class="col6">조회</span>
				</li>
<?php
	if (isset($_GET["page"]))
		$page = $_GET["page"];
	else
		$page = 1;

	$con = mysqli_connect("localhost", "stella", "stella@6767", "php연습");

    if(isset($_GET['search'])){ //검색바이다 주어진 변수가 있다면 받은 값들로 새변수들을 만들고 sql 검색해보자
                    $sel=$_GET['kind'];
                    $search=$_GET['search'];
                    $sql="select * from board where $sel like '%$search%' and catagory=1 order by num desc";
                    $result=mysqli_query($con,$sql);
    }

    else{ //검색창에 아무 값도 없이 클릭했다면 원래되로 모든 값 출력해주자 대신 catagory=1 즉 리뷰게시판 목록만 
    $sql = "select * from board where catagory=1 order by num desc";
    $result = mysqli_query($con, $sql);
}

	$total_record = mysqli_num_rows($result); // 전체 글 수

	$scale = 10;

	// 전체 페이지 수($total_page) 계산 
	if ($total_record % $scale == 0)     
		$total_page = floor($total_record/$scale);      
	else
		$total_page = floor($total_record/$scale) + 1; 
 
	// 표시할 페이지($page)에 따라 $start 계산  
	$start = ($page - 1) * $scale;      

	$number = $total_record - $start;

   for ($i=$start; $i<$start+$scale && $i < $total_record; $i++)
   {
      mysqli_data_seek($result, $i);
      // 가져올 레코드로 위치(포인터) 이동
      $row = mysqli_fetch_array($result);
      // 하나의 레코드 가져오기
	  $num         = $row["num"];
	  $id          = $row["id"];
	  $name        = $row["name"];
	  $subject     = $row["subject"];
      $regist_day  = $row["regist_day"];
      $hit         = $row["hit"];
	  $total       = $row["total"];     //댓글 수도 가져온다.

      if ($row["file_name"])
      	$file_image = "<img src='./img/file.gif'>";
      else
      	$file_image = " ";
?>
				<li>   
					<span class="col1"><?=$number?></span>
					<span class="col2"><a href="board_view.php?num=<?=$num?>&page=<?=$page?>"><?=$subject?> [<?=$total?>] </a></span>  <!-- 여기 나란히 넣어주자 -->
					<span class="col3"><?=$name?></span>
					<span class="col4"><?=$file_image?></span>
					<span class="col5"><?=$regist_day?></span>
					<span class="col6"><?=$hit?></span>
				</li>	
<?php
   	   $number--;
   }
   mysqli_close($con);

?>
	    	</ul>
			<ul id="page_num"> 	
<?php
	if ($total_page>=2 && $page >= 2)	
	{
		$new_page = $page-1;
		echo "<li><a href='board_list.php?page=$new_page'>◀ 이전</a> </li>";
	}		
	else 
		echo "<li>&nbsp;</li>";

   	// 게시판 목록 하단에 페이지 링크 번호 출력
   	for ($i=1; $i<=$total_page; $i++)
   	{
		if ($page == $i)     // 현재 페이지 번호 링크 안함
		{
			echo "<li><b> $i </b></li>";
		}
		else
		{
			echo "<li><a href='board_list.php?page=$i'> $i </a><li>";
		}
   	}
   	if ($total_page>=2 && $page != $total_page)		
   	{
		$new_page = $page+1;	
		echo "<li> <a href='board_list.php?page=$new_page'>다음 ▶</a> </li>";
	}
	else 
		echo "<li>&nbsp;</li>";


/*
	$c=1;*/
?>
			</ul> <!-- page -->	    

 
           <!-- 검색창 부분 get 방식으로 자기 php 문에 전달해주자 -->
        <form method=GET name=frm1 action='board_list.php' id="search">
            <select name=kind>
            <option value=subject selected>제목
            <option value=name>글쓴이
            <option value=content>내용
            </select>
            <input type=text size=30 name=search>
            <input type=button name=byn1 onclick="search1()" value="검색">
        </form>
               
 



			<ul class="buttons">
				<li><button onclick="location.href='board_list.php'">목록</button></li>
				<li>
<?php 
    if($userid) {
?>
					<button onclick="location.href='board_form.php'">글쓰기</button>
<?php
	} else {
?>
					<a href="javascript:alert('로그인 후 이용해 주세요!')"><button>글쓰기</button></a>
<?php
	}
?>
				</li>
			</ul>

	</div> <!-- board_box -->
</section> 
<footer>
    <?php include "footer.php";?>
</footer>

<script> //검색 창 함수 아무것도 입력이 안되어있으면 제자리 페이지로
    function search1(){
        if(frm1.search.value){
            frm1.submit();
        }else{
            location.href="board_list.php";
        }
    }
</script>


</body>
</html>
