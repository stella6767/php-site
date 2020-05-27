<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
<title>본격 편의점 리뷰</title>
<link rel="stylesheet" type="text/css" href="./css/board.css">
<link rel="stylesheet" type="text/css" href="./css/common.css">
</head>
<body> 
<header>
    <?php include "header.php";?>
</header>  
<section>
	<div id="main_img_bar"> 
		<img src="./img/main_img.png" >
    </div>
   	<div id="board_box">
	    <h3 id="board_title">
	    		게시판 > 글 쓰기
		</h3>
	    <form  name="board_form" method="post" action="board_insert.php" enctype="multipart/form-data">
	    	 <ul id="board_form">
				<li>
					<span class="col1">이름 : </span>
					<span class="col2"><?=$username?></span>
				</li>		
			<li>        <!-- 게시판 분류 작업 변수는 int로 넘겨서 구분하자 -->
				<tr>
					<td class="right" width=100 nowrap> <font class='pt7'>게시판 카테고리 :  </font> </td>
					<td class='pt7' style="text-align:left;">
				<select id=category name=catt> <option value=1>리뷰</option><option value=2>건의</option></select>	 
				
				</td>
				</tr>
			</li>

			<li>
				<tr>    <!-- 문자열 전달 제목에 합쳐짐 -->
					<td class="right" width=100 nowrap> <font class='pt7'>Special :  </font> </td>
					<td class='pt7' style="text-align:left;">
				<select id='category' name=category> <option value="">---------</option><option value="[e-mart24] ">e-mart24</option><option value="[CU] ">CU</option><option value="[GS25] ">GS25</option><option value="[7-11] ">7-11</option><option value="[기타] ">기타</option></select>	 
				
				</td>
				</tr>
			</li>


	    		<li>
	    			<span class="col1">제목 : </span>
	    			<span class="col2"><input name="subject" type="text"></span>
	    		</li>	    	
	    		<li id="text_area">	
	    			<span class="col1">내용 : </span>
	    			<span class="col2">
	    				<textarea name="content"></textarea>
	    			</span>
	    		</li>
	    		<li>
			        <span class="col1"> 첨부 파일</span>
			        <span class="col2"><input type="file" name="upfile"></span>
			    </li>
	    	    </ul>
	    	<ul class="buttons">
				<li><button type="button" onclick="check_input()">완료</button></li>
				<li><button type="button" onclick="location.href='board_list.php'">목록</button></li>
			</ul>
	    </form>
	</div> <!-- board_box -->
</section> 
<!-- 
	<footer>
		<?php include "footer.php";?>
	</footer> -->
				<script>
				  function check_input() {
					  if (!document.board_form.subject.value)
					  {
						  alert("제목을 입력하세요!");
						  document.board_form.subject.focus();
						  return;
					  }
					  if (!document.board_form.content.value)
					  {
						  alert("내용을 입력하세요!");    
						  document.board_form.content.focus();
						  return;
					  }
					  document.board_form.submit();
				   }
				</script>
</body>
</html>
