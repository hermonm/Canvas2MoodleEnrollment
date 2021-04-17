# Canvas to Moodle Student Enrollment
Each year every teacher resets their Moodle Course Shell and then enrolls next year's students. Some of us go ahead and manually enroll all of them ourselves, some use the group choice activity and some use the easy enrollment code methods. This program works by generating a CSV file from your Canvas Course and then redirects you to a Bulk Enrollment Page on your Moodle Course where you will then upload the file. 

Directions:
<ol>
<li>Moodle Admin installs the "Bulk Enrollments" plugin from https://docs.moodle.org/310/en/Bulk_enrolments</li>
<li>Moodle Admin opens the "config" file and adds in the Canvas Address and the Canvas Token</li>
<li>Moodle Admin FTPs the "XC2M" folder on your Moodle Server</li>
<li>Teacher enters their Canvas Course ID into Canvas Course ID box in their Moodle Course Settings.</li>
<li>Teacher goes to https://MOODLEADDRESS/XC2M/C2ME.php and clicks the course they want to enroll the students into.</li>
<li>A CSV file will be generated and downloaded and you will be redirected to the "Bulk Enrollment" page of your Moodle Course</li>
 <li>Upload the CSV file and your students will be enrolled into the course and placed in groups called: teacher username period number (Ex. hermonm7)</li>
 </ol>
 
 Here are the directions on YouTube as well:
