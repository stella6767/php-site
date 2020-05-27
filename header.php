<?php
    session_start();
    if (isset($_SESSION["userid"])) $userid = $_SESSION["userid"];
    else $userid = "";
    if (isset($_SESSION["username"])) $username = $_SESSION["username"];
    else $username = "";
    if (isset($_SESSION["userlevel"])) $userlevel = $_SESSION["userlevel"];
    else $userlevel = "";
    if (isset($_SESSION["userpoint"])) $userpoint = $_SESSION["userpoint"];
    else $userpoint = "";
?>      
        <div id="top">
            <h3>
                <a href="index.php">php 개발입문자의 하루하루</a>
            </h3>
            <ul id="top_menu">  
<?php
    if(!$userid) {
?>                
                <li><a href="member_form.php">회원가입</a> </li>
                <li> | </li>
                <li><a href="login_form.php">로그인</a></li>
<?php
    } else {
                $logged = $username."(".$userid.")님[Level:".$userlevel.", Point:".$userpoint."]";
?>
                <li><?=$logged?> </li>
                <li> | </li>
                <li><a href="logout.php">로그아웃</a> </li>
                <li> | </li>
                <li><a href="member_modify_form.php">정보수정</a></li>
<?php
    }
?>
<?php
    if($userlevel==1) {
?>
                <li> | </li>
                <li><a href="admin.php">관리자모드</a></li>
<?php
    }
?>
            </ul>
        </div>




<!-- Load font awesome icons -->
<link rel="stylesheet"  href="./css/menubar.css">



<ul id="topnav">
 <li><a href="index.php">HOME</a></li>
 <li>
     <a href="#">게시판</a>
     <!--Subnav Starts Here-->
     <span class="subnav1">
         <a href="board_list.php">게시판1</a> |
         <a href="board_list2.php">게시판2</a> 
     </span>
     <!--Subnav Ends Here-->
 </li>
 <li>
     <a href="#">Link</a>
     <!--Subnav Starts Here-->
     <span class="subnav2">
         <a href="#">Subnav Link</a> |
         <a href="#">Subnav Link</a> |
         <a href="#">Subnav Link</a>
     </span>
     <!--Subnav Ends Here-->
 </li>
 <li><a href="#">쪽지함</a>
 <span class="subnav2">
         <a href="message_box.php?mode=rv">수신 쪽지함</a> |
         <a href="message_box.php?mode=send">송신 쪽지함</a> 
     </span>

 </li>
 <li><a href="">미정</a></li>
</ul>














