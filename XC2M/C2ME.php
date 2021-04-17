<?php
require('../config.php');
require_login();
session_start();
include('config.php');


// Get list of all moodle course ids, CanvasIDs, Course Names.
$query="SELECT c.id AS CID, c.idnumber AS canvasID, c.shortname AS coursename,u.lastname,r.name

FROM mdl_course c
JOIN mdl_context ct ON c.id = ct.instanceid
JOIN mdl_role_assignments ra ON ra.contextid = ct.id
JOIN mdl_user u ON u.id = ra.userid
JOIN mdl_role r ON r.id = ra.roleid
WHERE r.name = 'Teacher' AND u.id = '$USER->id' AND c.idnumber!=''";

$result=mysqli_query($conn,$query);
$myrow=mysqli_fetch_array($result);
$rowcount=mysqli_num_rows($result);
$courselist=array();
if($rowcount){
do
{ //Get Moodle IDs, Canvas IDs and Course Names
  $course=array();
  $CID=$myrow['CID'];
  $canvasID=$myrow['canvasID'];
  $coursename=$myrow['coursename'];
  array_push($course, $CID);
  array_push($course, $canvasID);
  array_push($course, $coursename);

  array_push($courselist, $course);

}while($myrow=mysqli_fetch_array($result));}
  //Close Database connection
  mysqli_close($conn);

?>
<html>
<head>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php if(count($courselist)==1){?> <!--Automatically downloads if only one Moodle Course-->
<script type="text/javascript">
$(document).ready(function(){$('#mybutton').trigger('click');});

</script>
<?php }?>

<script type="text/javascript">

// Countdown timer for redirecting to another URL after several seconds

var seconds = 10; // seconds for HTML
var foo; // variable for clearInterval() function

function updateSecs() {
    document.getElementById("seconds").innerHTML = seconds;
    seconds--;
    if (seconds == -1) {
        clearInterval(foo);
    }
}

function countdownTimer() {
    foo = setInterval(function () {
        updateSecs()
    }, 1000);
}

countdownTimer();

      function insertText()
      {
        var elem = document.getElementById("theDiv");
        elem.innerHTML += "<img src='loading.gif'><br>Downloading and redirecting in <span id='seconds'>10</span> seconds.";
      }
    </script>

<style>
#mybutton{
    display: block;
    background-color: #255279;
    border: 1px solid black;
    width: 200px;
    height: 20;
    color: white;
    padding: 2px 2px;
    text-decoration: none;
    margin: 0px 0px;
    cursor: pointer;
    font-size: 12px;
    border-radius: 4px;
    outline: none;
    text-align: center;
}
#mybutton:hover {background-color: #3384ff;}

#mybutton:active {
  background-color: #3384ff;
  transform: translateY(2px);
}
h1 {font-size: 25px; text-decoration: underline; color: #255279; font-family: Papyrus;}
li {font-size: 14px;  color: #255279; font-family: Papyrus;}

img {width: 160px; height: 120px;}

</style>

</head>
<body>

<h1>Canvas to Moodle Student Enrollment</h1>

<div style="float:left;">
  <!--Downloading Buttons for each Moodle Course-->
  <?php foreach ($courselist as $Course) {?>
  <form action="C2MED.php">
      <input type="hidden" name="CID" value="<?=$Course[0]?>">
      <input type="hidden" name="CvID" value="<?=$Course[1]?>">
      <input type="hidden" name="Course" value="<?=$Course[2]?>">
      <input type="submit" onclick="goToMoodle('<?=$Course[0]?>'); insertText();" class="mybutton" id="mybutton" value="<?=$Course[2]?>" />
</form>
<?php }

if(count($courselist)==0){?>

  <ul>
    <li>No courses set up yet in Moodle. Please follow Setup Directions below and come back.</li>
  </ol>

  <?php }?>

</div>
<!---
<div style="float:left; color: red; font-size: 18px; font-family: Arial; padding-left: 20px; height: 100px; line-height: 100px;" id="theDiv"></div>
-->
<div style="float:left; text-align: center; color: red; font-size: 18px; font-family: Arial; padding-left: 20px; vertical-align: top;" id="theDiv"></div>



<div style="clear: left;">
<h1>Setup Directions</h1>
<ol>
  <li>This page will allow you to import all of your students from Canvas into Moodle at the beginning of the year.</li>
  <li>To do this you first need to align your Canvas and Moodle courses by entering the Canvas Course ID into the Availability & Canvas Sync section of your Moodle Course Settings.</li>
</ol>
<h1>Enroll Directions</h1>
<ol>
  <li>If you see no course buttons above you need to follow the Setup Directions above first.</li>
  <li>Click on the appropriate course button above to enroll students for that course. (If you only have one class this page will redirect automatically.)</li>
  <li>This will download a uniquely named CSV file and redirect you to your Moodle Bulk Enroll page.</li>
  <li>Select the downloaded CSV file and upload to enroll your students. Do not change any of the settings.</li>
  <li>In your Moodle course, groups will be named as teacherusername#: (ex. <b><?=$USER->username?>1, <?=$USER->username?>6, etc.</b>)</li>
  <li>If your groups had not already been created, this upload will create them along with groupings.</li>
  <li>You will receive an email if enrollment was successful.</li>
</ol>
</div>


<!--Redirects to Moodle Bulk Upload after CSV file is downloaded
Might have to increase from 10 seconds if it takes a long time for the file to download.
-->
<script>
    function goToMoodle(CID) {

        setTimeout(function () {
            document.location = "<?=$moodleAddress?>/local/mass_enroll/mass_enroll.php?id="+CID;
        }, 10000);
    }
    </script>


</body>
</html>
