
편의점 리뷰 게시판 제작

-------------

1. 편의점 제품들을 편하게 리뷰하고 의견을 교환할 수 있는 웹 페이지 구현

2. 기존 존재하던 웹 페이지 코드에서 큰 변화 없이 추가 기능과 약간 스타일 변경 점만 개선

3. 구현한 기능들

	3-1. 드롭다운 수평메뉴바

	3-2. 게시판 검색창

	3-3. 댓글창 기능 

	3-4. 게시판 카테고리 분류란과 항목 선택 분류란   

	3-5. 댓글 합계 총 수 구현
	 


리뷰게시판에 들어가 게시판 글의 키워드를 파악해 전체 상품 목록 리뷰를 살펴볼 수 있으며 게시글 작성 시 유저가 자기 글의 상품 키워드를 설정할 수 있도록 구현했다.



-추가한 sql 코드-

```
CREATE TABLE `board` (
  `num` int(11) NOT NULL,
  `id` char(15) NOT NULL,
  `name` char(10) NOT NULL,
  `subject` char(200) NOT NULL,
  `content` text NOT NULL,
  `regist_day` char(20) NOT NULL,
  `hit` int(11) NOT NULL,
  `file_name` char(40) DEFAULT NULL,
  `file_type` char(40) DEFAULT NULL,
  `file_copied` char(40) DEFAULT NULL,
  `catagory` int(11) DEFAULT NULL, //추가= 카테코리 분류를 위해 int 형 변수 필드를 만들었다.
  `total` int(11) NOT NULL // 추가= 댓글 총 개수를 출력하는 데 활용된다.
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE `comment` (  //새로 만든 코멘트 데이터 테이블
  `id` char(15) NOT NULL,
  `name` char(10) NOT NULL,
  `comment` text NOT NULL,
  `regist_day` char(20) NOT NULL,
  `sel` int(11) DEFAULT NULL  //각 게시글에 등록된 댓글목록만 보여주기 위해 만들었다.
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

나머지 members, message sql 코드는 변경이 없다.
기존 php 문에서 새로 만든 php(board_list2.php, comment_insert.php)
나머지는 기존 코드에 추가 및 수정함
추가한 코드에 한 해 css는 별도로 지정해주었다. 


1. 드롭다운 수평메뉴바 구현
```
<nav id="topMenu" > 
    <ul> 
        <li class="topMenuLi"> 
            <a class="menuLink" href="index.php">HOME</a>    
        </li> 
            <li>|</li> 
        <li class="topMenuLi"> 
            <a class="menuLink" href="#">게시판</a> 
                <ul class="submenu"> 
                <li><a href="board_list.php" class="submenuLink longLink">리뷰 게시판</a></li> 
                <li><a href="board_list2.php" class="submenuLink longLink">건의 게시판</a></li>
            </ul> 
        </li> 
            <li>|</li> 
            <li class="topMenuLi"> <a class="menuLink" href="#">LINK</a> 
                <ul class="submenu">    
                    <li><a href="http://cu.bgfretail.com/index.do"  target="_blank" class="submenuLink longLink">CU</a></li>
                    <li><a href="http://gs25.gsretail.com/gscvs/ko/main"  target="_blank" class="submenuLink longLink">GS25</a></li> 
                    <li><a href="https://www.emart24.co.kr/index.asp"  target="_blank" class="submenuLink longLink">E-MART 24</a></li>
                    <li><a href="http://www.7-eleven.co.kr/"  target="_blank" class="submenuLink longLink">7-11</a></li>
                    <li><a href="https://www.ministop.co.jp/in/kr/"  target="_blank" class="submenuLink longLink">MINI STOP</a></li>
                </ul> 
            </li> 
                <li>|</li> 
            <li class="topMenuLi"> <a class="menuLink" href="#">쪽지란</a> 
                <ul class="submenu2">    
                    <li><a href="message_box.php?mode=rv" class="submenuLink longLink">수신 쪽지함</a></li> 
                    <li><a href="message_box.php?mode=send" class="submenuLink longLink">송신 쪽지함</a></li> 
                </ul> 
            </li>           
            </ul> 
        </nav>

```



기존 존재하던 header 코드에 메뉴창 코드를 작성해준다. 각각의 메뉴바에 css를 적용시키기 위해 class id 명을 따로 지정해주었다. <href="#" > 메뉴를 클릭할 때 아무런 반응도 일어나지 않도록 설정하고 target="_blank"로 새 창 띄우기는 링크 메뉴판에 만들었다. 이후 메뉴바에 관한 css 작성하였다.
```
<style type="text/css">
    #topMenu {
            height: 50px;  
            width: 1263px;  
            position: relative; /* 하위 메뉴 시작 지점을 메뉴와 동일하게 설정하기 위함 */

    }
    #topMenu ul {           /* 메인 메뉴 안의 ul을 설정함: 상위메뉴의 ul+하위 메뉴의 ul */
        list-style-type: none;  
        margin: 0px;            
        padding: 0px;       
    }
    #topMenu ul li {            /* 메인 메뉴 안에 ul 태그 안에 있는 li 태그의 스타일 적용(상위/하위메뉴 모두) */
        color: white;               
        background-color:  #ccc;  
        float: left;                /*수평으로 펼치는 기능 */        
        line-height: 50px;          
        vertical-align: middle;     
        text-align: center;         
    }
    .menuLink, .submenuLink {           /* 상위 메뉴와 하위 메뉴의 a 태그에 공통으로 설정할 스타일 */
        text-decoration:none;               
        display: block;                     
        width: 313px;                       
        font-size: 15px;                    
        font-weight: bold;                  
        font-family: "Trebuchet MS", Dotum; 
    }
    .menuLink {     /* 상위 메뉴의 글씨색을 흰색으로 설정 */
        color: white;
    }
    .topMenuLi:hover .menuLink {    /* 상위 메뉴의 li에 마우스오버 되었을 때 스타일 설정 */
        color: red;                 
        background-color: #4d4d4d;
    }
    .longLink {     /* 좀 더 긴 메뉴 스타일 설정 */
        width: 190px;   
    }
.submenuLink {          /* 하위 메뉴의 a 태그 스타일 설정 */
        color: #2d2d2d;             
        background-color: white;    
        border: solid 1px black;    
        margin-right: -1px;         
    }
    .submenu {              /* 하위 메뉴 스타일 설정 */
        position: absolute;     
        height: 0px;            
        overflow: hidden;       
        transition: height .2s; 
        -webkit-transition: height .2s; 
        -moz-transition: height .2s; 
        -o-transition: height .2s; 
        width: 970px;           /* 가로 드랍다운 메뉴의 넓이 */
        left: 250px;               /* 왼쪽부터 픽셀 공백 만큼 하위 메뉴 위치가 나오게 */
     /*   background-color: white;  */
    }
    .submenu2 {              /* 하위 메뉴 스타일 설정 */
        position: absolute;     
        height: 0px;            
        overflow: hidden;       /*굳이 하나 더 만든 이유는 스크롤 바 안 생기도록*/
        transition: height .2s; 
        -webkit-transition: height .2s; 
        -moz-transition: height .2s; 
        -o-transition: height .2s; 
        width: 390px;           /* 가로 드랍다운 메뉴의 넓이 */
        left: 875px;              /* 왼쪽부터 픽셀만큼 하위 메뉴 위치가 나오게 */
     /*   background-color: white;  */
    }
    .submenu li {
        display: inline-block;  /*하위 메뉴바 수평기능*/
    }
    .submenu2 li {
        display: inline-block;  /*하위 메뉴바 수평기능*/
    }
    .topMenuLi:hover .submenu { 
        height: 52px;           
    }
    .topMenuLi:hover .submenu2 { 
        height: 52px;           
    }
    .submenuLink:hover {   /*하위 메뉴바 마우스 올릴때 색깔*/
        color: red;                 
        background-color: #dddddd;  
    }
    </style>
```
메뉴 창마다 하위메뉴가 떠오르는 위치를 다르게 지정해주다보니 코드가 전체적으로 길어졌다. 원래는 css 파일을 따로 지정해주어서 연결시키려하였는데 도중에 오류가 자꾸 떠서 <header.php> 파일에 따로 스타일 시트를 만들었다. 전체 설명은 주석으로 대체한다.

![image](https://user-images.githubusercontent.com/65489223/97440201-8267ca80-196a-11eb-90f9-273c092b1aa7.png)
                   <메뉴판에 마우스오버하면 하위메뉴창이 뜬다>

2. 검색 창 구현
 ```
        <form method=GET name=frm1 action='board_list.php' id="search">
            <select name=kind>
            <option value=subject selected>제목
            <option value=name>글쓴이
            <option value=content>내용
            </select>
            <input type=text size=30 name=search>
            <input type=button name=byn1 onclick="search1()" value="검색">
        </form>
 ```              
기존에 다운받은 php 코드 중 board_list.php 부분에 검색창 기능을 하는 form 문 코드 작성
option value는 제목 선택으로 고정해놓고 이 후 선택 가능 검색 txt 내용 작성하면 onclik 버튼으로 search1 함수 호출  전달방식은 GET 방식으로 자기 php(board_list.php) 에 전달하도록 설정. value 부분에는 꼭 sql 구문에 맞는 필드명들이 들어가야 한다. 그래야 나중에 이 값으로 변수를 저장해 sql 검색 명령어에 제대로 대입시킬 수 있음.

```
<script>
    function search1(){
        if(frm1.search.value){
            frm1.submit();
        }else{
            location.href="board_list.php";
        }
    }
</script>
</body>
```
body 윗부분에 함수를 작성해준다. 만약 input 박스에 값이 없다면 원래 주소로 위치하고 있다면 값을 전송해준다.
전송된 값은 바로 윗 부분 mysql db와 연동된다.

![image](https://user-images.githubusercontent.com/65489223/97440223-872c7e80-196a-11eb-9e22-3d83453dce1a.png)
                                              <구현된 검색창>
```
    if(isset($_GET['search'])){
                    $sel=$_GET['kind'];
                    $search=$_GET['search'];
                    $sql="select * from board where $sel like '%$search%' order by num desc";
                    $result=mysqli_query($con,$sql);
    }

    else{
    $sql = "select * from board order by num desc";
    $result = mysqli_query($con, $sql);
}
```
기존 sql 코드에 이 구문만 추가해준다. 만약 검색창에 값이 입력되어있다면 함수가 정상적으로 작동하고 값을 전달해줄 것이다. 전달된 값을 get으로 받고 isset 함수로 이것이 있다는 게 확인된다면 각자 변수들을 만들어 제목 글쓴이 내용 란의 select value 값을 sel 변수에 저장하고 종류별로 텍스트 내용을 search 변수에 저장한다. 그리고 select 조건문을 사용해 그 검색종류란에 맞는 필드별로 search 내용이 포함된 결과를 num 기준으로 내림차순으로 정렬한다
만약 search 값이 전달되지 않는다면 기존 코드 그대로 select 문을 수행한다.


![image](https://user-images.githubusercontent.com/65489223/97440236-8b589c00-196a-11eb-8b8b-9968bbd9dd91.png)
                                  <그렇게 구현된 검색창 ‘테’ 검색 결과>

                       
검색창 css:  #search {  width: 50%; margin-left : 25% }  지정해준다. common.css 파일 안에 있음





3. 댓글기능 구현
```
create table comment (
   id char(15) not null,
   name char(10) not null,
   comment text not null,        
   regist_day char(20) not null,
   sel int(11)
);
```
```
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
                <li>
				 <button type="button" onclick="check_input()">댓글쓰기</button></li>
            </ul>
        </form>
      ```
댓글을 입력받고 전달하는 댓글 창 코드이다. post 방식으로 comment_insert.php 부분에 전달해준다.  <board_view.php> 밑 부분에 작성해준다. form 문 안에서 a href 로 변수들을 여러개 동시에 전달하는 것은 무슨 이유에서인지 안 되는 것 같다. input value로 전달해주다. 
```
<script>
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
```

댓글쓰기 버튼을 클릭했을 때 comment 값이 입력되어 있지 않는다면 경고를 울리고 돌아가도록 설정하고 아니면 원래 action에 “comment_insert.php" 로 전송기능을 하는 함수이다. 

<comment_insert.php>

comment_insert.php는 기존 board_insert와 거의 유사하게 작성하였다. 다음 코드는 comment_insert 파일로 데이터를 저장하기 위한 코드이다. 
```
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


	
	$con = mysqli_connect("localhost", "user1", "12345", "sample");

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
``` 
추가한 코드 설명은 주석에 있다. 짤막하게 설명하자면 여기서 댓글 입력받아서 보낸 변수 comment, num, page를 받고 comment와 num 변수는 sql comment 테이블 comment, sel필드로 삽입한다. page 변수는 나중에 명령을 수행하고 board_view 로 다시 돌아올 때 오류가 생기지 않도록 필요하고 new_total 변수도 만들어 board 테이블의 total 필드(댓글 수) 값을 하나씩 증가하도록 시킨다. 조건문은 당연히 같은 게시글 num과 일치하는지의 여부이다.




```
<board_view.php>

<?php
   
   mysqli_close($con); //위에 연결한 거 끊어주고 

	$con = mysqli_connect("localhost", "user1", "12345", "sample");
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
```
댓글 테이블에서 정보들을 가져와 댓글목록을 뽑아내는 코드이다. 위에 먼저 연결한 sql을 끊어야 오류가 안 생김. 



![image](https://user-images.githubusercontent.com/65489223/97440258-91e71380-196a-11eb-8f89-7ef2a35f4a0c.png)

                                            <구현된 댓글창과 목록>



4. 게시판 카테고리 분류란과 항목 선택 분류란 

먼저 board_list2.php 파일을 만든다. 만드는 양식은 기존 board_list.php 와 거의 유사하고 하이퍼링크만 다르며 둘은 같은 데이터테이블을 공유하기 때문에 board_list.php 추가 구문 설명만 하고 따로 설명을 하지는 않겠다. 일단 글쓰기 양식 board_form.php 문을 수정해보았다.

<board_form.php>
```

<li>														<tr>														<td class="right" width=100 nowrap> <font class='pt7'>게시판 카테고리 :  </font> </td>
					<td class='pt7' style="text-align:left;">										<select id=category name=catt> <option value=1>리뷰</option><option value=2>건의</option></select>	 													</td>
						</tr>
				</li>

																<li>	
				<tr>
					<td class="right" width=100 nowrap> <font class='pt7'>Special :  </font> </td>
				<td class='pt7' style="text-align:left;">
	<select id='category' name=category> <option value="">---------</option><option value="[e-mart24] ">e-mart24</option><option value="[CU] ">CU</option><option value="[GS25] ">GS25</option><option value="[7-11] ">7-11</option><option value="[기타] ">기타</option></select>	 
					</td>
				</tr>
			</li>
```
보시다시피 form 문 안에 변수 전달하는 영역을 두 개 만들었다.
하나는 sel 변수로 전달할 것이고 하나는 cat 변수로 전달해 subject 와 합칠 것이다.


![image](https://user-images.githubusercontent.com/65489223/97440268-94e20400-196a-11eb-81a7-4d72109b67cb.png)

<board_insert.php>
```
$catt = $_POST["catt"]; //게시판 분류에 필요
    $subject = $_POST["subject"];
    $content = $_POST["content"];
    $cat     = $_POST['category'];
	$subject = $cat.$subject; 



$sql = "insert into board (id, name, subject, content, regist_day, hit,  file_name, file_type, file_copied, catagory,total) ";
	$sql .= "values('$userid', '$username', '$subject', '$content', '$regist_day', 0, ";
	$sql .= "'$upfile_name', '$upfile_type', '$copied_file_name', '$catt',0)";
	mysqli_query($con, $sql);  // $sql 에 저장된 명령 실행


echo "
	   <script>
	   	if($catt==1)
	    location.href = 'board_list.php';
	    else
	    location.href = 'board_list2.php';

	   </script>
	";
```

기존 코드에 따로 추가한 구문들이다. 보면 알다시피 문자열 cat 변수는 subject와 함쳐져 제목에 나타날 것이고 게시판 분류에 필요한 1이나 2인 catt 변수는 catagory로 전달될 것이다.  댓글 total에는 0을 입력한다. 만약 catt 값이 1이라면 기존 board_list(리뷰게시판) 아니면 board_list2(건의 게시판)으로 이동할 것이다. 

5. 댓글 총 수 구현

<board_list.php>
```
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



$total       = $row["total"]; 

             <span class="col2"><a href="board_view.php?num=<?=$num?>&page=<?=$page?>"><?=$subject?> [<?=$total?>] </a></span> 
```
앞서 설명한 코드들이다. 글쓰기에서 완료한 category 변수를 기준으로 board 테이블의 데이터 목록을 뽑아낸다는 것을 알 수 있다. 새로 total (댓글 총수) 변수도 뽑아내고 제목 옆에 알 수 있도록 나타내어준다.


<board_view.php>
```
$category     = $row["catagory"];

	if($category==1) //추가한 거 위의 h3 과 연결
		echo "리뷰 게시판 > 목록 보기";
	else
		echo "건의 게시판 > 목록 보기";


입력된 category 변수에 따라 리뷰 게시판인지 건의 게시판인지 화면에 뜨도록 설정하였다.


<main.php>

 if (!$result)
        echo "게시판 DB 테이블(board)이 생성 전이거나 아직 게시글이 없습니다!";
    else
    {
        while( $row = mysqli_fetch_array($result) )   //최근 게시글 부분에 하이퍼링크를 넣어주자
        {
            $regist_day = substr($row["regist_day"], 0, 10);
            $num        = $row["num"];
            $page = 1;
 ?>               <li>
                    <span><a href="board_view.php?num=<?=$num?>&page=<?=$page?>"><?=$row["subject"]?>  [<?=$row["total"]?>]</a></span> <!-- 댓글 수도 같이 넣어주자 -->
                    <span><?=$row["name"]?></span>
                    <span><?=$regist_day?></span>
                </li>


```
최근 게시글 항목을 보여주는 main.php 항목에는 하이퍼링크와 댓글 총수도 같이 출력나오도록 설정해준다
아래는 작업한 css 파일이다.
```
<common.css>
#main_img_bar img { height: 230px; text-align: center; margin-top: 0px;  width: 1400px; }
#main_img_bar2 img { height: 230px; text-align: center; margin-top: 0px;  width: 1422px; }
#category {  width: 10%; margin-left : 2% } 

<board.css>
/* 댓글창 목록보기 (board_view.php) */
#comment_list li { padding: 5px 0; border-bottom: solid 1px #dddddd; }
#comment_list span { display: inline-block; text-align: center; padding-bottom: 10px;}
#comment_list .co1 { width: 80px;  vertical-align:top; }
#comment_list .co2 { width: 600px; text-align: left; }
#comment_list .co3 { width: 80px; text-align: right; font-size: xx-small; vertical-align:top; }
/* 댓글창 글쓰기 (board_view.php) */
#comment_form span { display: inline-block; }
#comment_form .col1 { width: 62px; }
#comment_form li {	padding: 12px; border-bottom: solid 1px #dddddd; }
#comment_form textarea { width: 600px;	height: 100px; }
#comment_form #text_area {	position: relative;	height: 100px; }
#comment_form #text_area .col1 { position: absolute; top: 10px; }
#comment_form #text_area .col2 { position: absolute; left: 140px; }
#list li { padding: 0px 0; border-bottom: solid 1px #dddddd; }
```

<추가>
현재 시각을 한국 기준으로 설정하려 data 함수를 바꿈 date.timezone Asia/Seoul로 바꿈



