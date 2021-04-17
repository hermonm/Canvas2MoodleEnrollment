<?php
require('../config.php');
require_login();
include('config.php');


//Get Moodle Course ID, Canvas ID and Course Name from the selected button.
if (isset($_REQUEST['CID'])) {$CID=$_REQUEST['CID'];}
if (isset($_REQUEST['CvID'])) {$canvasID=$_REQUEST['CvID'];}
if (isset($_REQUEST['Course'])) {$coursetitle=$_REQUEST['Course'];}


    //Make array from Canvas with Canvas $sectionid-> Moodle $groupname (ex. hermon8)
    $sectiondata = json_decode(@file_get_contents($canvasAddress.'/api/v1/courses/'.$canvasID.'/sections?per_page=100&access_token='.$canvasToken));
    $mysections = array();
    foreach($sectiondata as $section)
    {
    $pos = strcspn( $section->name , '0123456789'); //strip position of number out of canvas section name
    $mysections[$section->id]=$USER->username.$section->name[$pos];
    }

    //Make a Moodle array for ALL moodle users: $username->$idnumber
    $querys="SELECT u.username AS username, u.idnumber AS idnumber FROM mdl_user u WHERE u.idnumber!=''";
    $results=mysqli_query($conn,$querys);
    $myrows=mysqli_fetch_array($results);
    $e_id = array();
    do {
      $idnumber=$myrows["idnumber"];
      $username=$myrows["username"];
      $e_id[$username]=$idnumber;
    }while($myrows=mysqli_fetch_array($results));

//Make final array for CSV with moodle student id->$groupname
//Needed to do this by section instead of course to stay under the 100 per_page limit!!!!!

$myCSV = array();
$header = array('Username', 'Group name');
array_push($myCSV, $header);

foreach($mysections as $SID => $groupname){//begin

    $studentdata = json_decode(@file_get_contents($canvasAddress.'/api/v1/sections/'.$SID.'/enrollments?type=StudentEnrollment&grouped=true&per_page=100&access_token='.$canvasToken));

    foreach($studentdata as $canvas_student)
    {$student = array();
      $email = $canvas_student->user->login_id;
      $email_explode = explode("@", $email);
      $username = $email_explode[0];
      $id = $e_id[$username]; //search $e_id array to get id number of corresponding username.
      array_push($student, $id);
      array_push($student, $groupname);
      array_push($myCSV, $student);
}


//end
}
  //Close Database connection
  mysqli_close($conn);


//Create CSV File!!!!

$comma=',';
$filename = $coursetitle."_enroll_".date('Y-m-d h.i a').".csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');
$f = fopen('php://output', 'w');
foreach($myCSV as $student) {
    fputcsv($f, $student, $comma);
  }
 exit; ?>
