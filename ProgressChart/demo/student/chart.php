<link rel="icon" type="image/x-icon" href="https://cdn.discordapp.com/attachments/960423388369813514/1119515459730026526/logo.png">
<?php session_start(); ?>
<?php include "../../Frontend/assets/header.php"; ?>
<?php include "../../Frontend/assets/user_nav.php"; ?>
<?php //include "../function/connect.php";
    $con = mysqli_connect("localhost","root","kittichai","pcg_db")or die("err!");
    $sql_task = "SELECT count(pg_id) AS task_unfinish, sum(score_std) AS total_score FROM progress WHERE sj_id = '".$_REQUEST['sj_id']."' AND usr_no_id = '".$_SESSION['no_usr']."' AND ack_std = '0' ";
$query_task = mysqli_query($con,$sql_task);
$task_data = mysqli_fetch_array($query_task);

$sql_ftask = "SELECT count(pg_id) AS task_finish, sum(score_std) AS total_score FROM progress WHERE sj_id = '".$_REQUEST['sj_id']."' AND usr_no_id = '".$_SESSION['no_usr']."' AND ack_std = '1' AND ack_teacher = '0' ";
$query_ftask = mysqli_query($con,$sql_ftask);
$ftask_data = mysqli_fetch_array($query_ftask);

$sql_donetask = "SELECT count(pg_id) AS finished, sum(score_std) AS total_score FROM progress WHERE sj_id = '".$_REQUEST['sj_id']."' AND usr_no_id = '".$_SESSION['no_usr']."' AND ack_std = '1' AND ack_teacher = '1' ";
$query_donetask = mysqli_query($con,$sql_donetask);
$donetask_data = mysqli_fetch_array($query_donetask);

$sql_alltask = "SELECT count(pg_id) AS all_task, sum(score) AS total_score , sum(score_std) AS total_score_std FROM progress WHERE sj_id = '".$_REQUEST['sj_id']."' AND usr_no_id = '".$_SESSION['no_usr']."'";
$query_alltask = mysqli_query($con,$sql_alltask);
$alltask_data = mysqli_fetch_array($query_alltask);

$text_data = $task_data['task_unfinish'].",".$ftask_data['task_finish'].",".$donetask_data['finished'];
?>
<script>
    // TW Elements is free under AGPL, with commercial license required for specific uses. See more details: https://tw-elements.com/license/ and contact us for queries at tailwind@mdbootstrap.com 
// Initialization for ES Users
import {
  Chart,
  initTE,
} from "tw-elements";

initTE({ Chart });

</script>
<section class="m-1 w-full">
    <div class="grid justify-items-stretch ">
        <div class="justify-self-center">
            <div class="h-80 w-90">
            <canvas class="p-1 ml-40 mr-40 " id="chartPie"></canvas>
            </div>
        </div>
    
    </div>
 
<!-- Required chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart pie -->
<script>
  const dataPie = {
    labels: ["ยังไม่เสร็จ", "เสร็จแล้วรอตรวจ", "ตรวจแล้ว"],
    datasets: [
      {
        label: "เปอร์เซ็นการดำเนินงาน",
        data: [<?php echo $text_data;?>],
        backgroundColor: [
          "rgb(255, 0, 0)",
          "rgb(255, 128, 0)",
          "rgb(51, 204, 0)",

        ],
        hoverOffset: 4,
      },
    ],
  };

  const configPie = {
    type: "pie",
    data: dataPie,
    options: {},
  };

  var chartBar = new Chart(document.getElementById("chartPie"), configPie);
</script>

<div class="grid justify-items-stretch">
    <div class="justify-self-center">
    <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">จำนวนงานทั้งหมด <?php echo $alltask_data['all_task']?> งาน</h5>
    <p class="font-normal text-gray-700 dark:text-gray-400">คะแนนเก็บสะสม <?php echo $alltask_data['total_score_std']?> คะแนน จาก <?php echo $alltask_data['total_score']?> คะแนน </p>
    </a>
    </div>
</div>

<?php

    $sql_unfinishtask = "SELECT * FROM progress WHERE sj_id = '".$_REQUEST['sj_id']."' AND usr_no_id = '".$_SESSION['no_usr']."' AND ack_std = '0' AND ack_teacher = '0' ";
    $query_unfinishtask = mysqli_query($con,$sql_unfinishtask);
    

?>
<div class="h-10 shadow">
    <p>
    <span class="shadow bg-red-100 text-red-800 text-xs font-large me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">ยังไม่ได้ส่งงาน</span>
    </p>
</div>
<div class="bg-gray-600 rounded">
    <table class="table-fixed w-full">
    <thead>
        <tr>
        <th class="self-center" style="color:white;">วิชา</th>
        <th class="self-center" style="color:white;">งานที่ต้องดำเนินการ</th>
        <th class="self-center" style="color:white;">คะแนนเต็ม</th>
        <th class="self-center" style="color:white;">คะแนนที่ได้</th>
        <th class="self-center" style="color:white;">สถานะการตรวจ</th>
        <th class="self-center" style="color:white;">แจ้งดำเนินการ</th>
        </tr>
    </thead>
    <tbody  class="justify-items-center">
        
<?php
    $colorflag = 0;
    while($unfinishtask = mysqli_fetch_array($query_unfinishtask)){  
        $sql_sj = "SELECT * FROM subject WHERE sj_id = '".$unfinishtask['sj_id']."'";
        $query_sj = mysqli_query($con,$sql_sj);
        $sj_data = mysqli_fetch_array($query_sj);
        if($colorflag == 0){
            ?>
                <tr class="bg-blue-100">
            <?php

            $colorflag = 1;
        }else if($colorflag == 1){
            ?>
                <tr class="bg-blue-300">
            <?php
            $colorflag = 0;
        }
?>

        <td ><center><?php echo $sj_data['sj_name'];?></center></td>
        <td ><center><?php echo $unfinishtask['task_name'];?></center></td>
        <td ><center><?php echo $unfinishtask['score'];?></center></td>
        <td ><center><?php echo $unfinishtask['score_std'];?></center></td>
        <td ><center><?php echo ($unfinishtask['ack_teacher']!=true)?"ยังไม่ตรวจ":"ตรวจแล้ว";?></center></td>
        <td ><center>
            <a href="./followup.php?pg_id=<?php echo $unfinishtask['pg_id'];?>">
            <button type="button" class="bg-green-500 hover:bg-green-700  font-bold py-2 px-4 rounded">แจ้งส่งงาน</button>
            </a>
        </center></td>
        </tr>
   

<?php
    }
    
?>
    </tbody>
    </table>
    </div>

    <div class="h-10 "></div>
    <?php

$sql_waittask = "SELECT * FROM progress WHERE sj_id = '".$_REQUEST['sj_id']."' AND usr_no_id = '".$_SESSION['no_usr']."' AND ack_std = '1' AND ack_teacher = '0' ";
$query_waittask = mysqli_query($con,$sql_waittask);


?>
<div class="h-10 shadow">
    <p>
    <span class="shadow bg-yellow-100 text-yellow-800 text-xs font-large me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-red-300">งานรอตรวจ</span>
    </p>
</div>
<div class="bg-gray-600 rounded">
<table class="table-fixed w-full">
<thead>
    <tr>
    <th class="self-center" style="color:white;">วิชา</th>
    <th class="self-center" style="color:white;">งานที่ต้องดำเนินการ</th>
    <th class="self-center" style="color:white;">คะแนนเต็ม</th>
    <th class="self-center" style="color:white;">คะแนนที่ได้</th>
    <th class="self-center" style="color:white;">สถานะการตรวจ</th>
    <th class="self-center" style="color:white;">ดำเนินการ</th>
    </tr>
</thead>
<tbody  class="justify-items-center">
    
<?php
$colorflag = 0;
while($waittask = mysqli_fetch_array($query_waittask)){  
    $sql_sj = "SELECT * FROM subject WHERE sj_id = '".$waittask['sj_id']."'";
    $query_sj = mysqli_query($con,$sql_sj);
    $sj_data = mysqli_fetch_array($query_sj);
    if($colorflag == 0){
        ?>
            <tr class="bg-blue-100">
        <?php

        $colorflag = 1;
    }else if($colorflag == 1){
        ?>
            <tr class="bg-blue-300">
        <?php
        $colorflag = 0;
    }
?>

    <td ><center><?php echo $sj_data['sj_name'];?></center></td>
    <td ><center><?php echo $waittask['task_name'];?></center></td>
    <td ><center><?php echo $waittask['score'];?></center></td>
    <td ><center><?php echo $waittask['score_std'];?></center></td>
    <td ><center><?php echo ($waittask['ack_teacher']!=true)?"ยังไม่ตรวจ":"ตรวจแล้ว";?></center></td>
    <td ><center><button type="button" class="font-bold py-2 px-4 rounded">ส่งงานแล้ว</button></center></td>
    </tr>


<?php
}

?>
</tbody>
</table>
</div>


<div class="h-10 "></div>
    <?php

$sql_waittask = "SELECT * FROM progress WHERE sj_id = '".$_REQUEST['sj_id']."' AND usr_no_id = '".$_SESSION['no_usr']."' AND ack_std = '1' AND ack_teacher = '1' ";
$query_waittask = mysqli_query($con,$sql_waittask);


?>
<div class="h-10 shadow">
    <p>
    <span class="shadow bg-green-100 text-green-800 text-xs font-large me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-red-300">ตรวจแล้ว</span>
    </p>
</div>
<div class="bg-gray-600 rounded">
<table class="table-fixed w-full">
<thead>
    <tr>
    <th class="self-center" style="color:white;">วิชา</th>
    <th class="self-center" style="color:white;">งานที่ต้องดำเนินการ</th>
    <th class="self-center" style="color:white;">คะแนนเต็ม</th>
    <th class="self-center" style="color:white;">คะแนนที่ได้</th>
    <th class="self-center" style="color:white;">สถานะการตรวจ</th>
    <th class="self-center" style="color:white;">ดำเนินการ</th>
    </tr>
</thead>
<tbody  class="justify-items-center">
    
<?php
$colorflag = 0;
while($waittask = mysqli_fetch_array($query_waittask)){  
    $sql_sj = "SELECT * FROM subject WHERE sj_id = '".$waittask['sj_id']."'";
    $query_sj = mysqli_query($con,$sql_sj);
    $sj_data = mysqli_fetch_array($query_sj);
    if($colorflag == 0){
        ?>
            <tr class="bg-blue-100">
        <?php

        $colorflag = 1;
    }else if($colorflag == 1){
        ?>
            <tr class="bg-blue-300">
        <?php
        $colorflag = 0;
    }
?>

    <td ><center><?php echo $sj_data['sj_name'];?></center></td>
    <td ><center><?php echo $waittask['task_name'];?></center></td>
    <td ><center><?php echo $waittask['score'];?></center></td>
    <td ><center><?php echo $waittask['score_std'];?></center></td>
    <td ><center><?php echo ($waittask['ack_teacher']!=true)?"ยังไม่ตรวจ":"ตรวจแล้ว";?></center></td>
    <td ><center><button type="button" class="font-bold py-2 px-4 rounded disabled">ส่งและตรวจงานแล้ว</button></center></td>
    </tr>


<?php
}

?>
</tbody>
</table>
</div>
</section>