<?php 
 session_start();
    //declare CWID variable
    $CWID = $_POST['cwid'];

    //======== BIGIN INPUT PARSING ===========
    //check for user input errors such as missing CWID or less than 9 digits
    //if error is found, kick the user back to index, following an error msg
    if(!$CWID){
        $_SESSION['message'] = "Please enter CWID";
        header("Location: index.php");
    }elseif(strlen($CWID) < 9){
        $_SESSION['message'] = "CWID must be 9 digits";
        header("Location: index.php");
    }elseif(!is_numeric($CWID)){
        $_SESSION['message'] = "Please enter numeric 9 digits";
        header("Location: index.php");
    }

    //========= END INPUT PARSING =============
    //if error checking goes smoothly, we can begin quetying the database
    //using mysqli so MAMP doesn't complain. will need to change it back to regular
    //mysql later
    
    else{
        //local variables to connect to database
        $user = 'root';
        $password = 'root';
        $db = 'hw3';
        $host = '127.0.0.1';
        $port = 8889;
        $socket = 'localhost:/Applications/MAMP/tmp/mysql/mysql.sock';

        //link to database

        $link = mysqli_init();
        $success = mysqli_real_connect($link, $host, $user, $password, $db, $port, $socket);
        if (mysqli_connect_errno()) {
            echo '<html><body>'; 
            echo "<p>Error: Could not connect to data base.  Try again<p>\n";
            exit;
        } 
        //database connected

        //see if the student with CWID exists
        $query = "select * from course_score where cscwid = ".$CWID;

        //Send in the query to MySQL and get the results.
			  $result = $link->query($query) or die("ERROR: " . mysqli_error($link));

        $num_results = mysqli_num_rows($result);
        if ($num_result === 0) {
            $_SESSION['message'] = "The student does not exist. Please enter valid CWID";
            header("Location: index.php");
        }
        else { //student does exist

            //result holds query result of scores for the student

            //declare constants
            define ('PERCENTAGE_ATEENDANCE_SCORE', 0.05);
            define ('PERCENTAGE_AVG_HW_SCORE', 0.2);
            define ('PERCENTAGE_PROJECT', 0.2);
            define ('PERCENTAGE_MIDTERM', 0.25);
            define ('PERCENTAGE_FINAL', 0.3);
         

            $att_score = 0;    //attendance score
            $avg_hw_score = 0; //average homework score
            $proj_score = 0;   //project score
            $m_score = 0;      //midterm score
            $f_score = 0;      //final score
            $student_avg = 0;  //average the score of given student
 
            //look for homework_score to get average of homework       
            $hw_query = "select * from homework_score where course_score_cwid = ".$CWID;
            $hw_total_score = 0;
            $hw_avg_score = 0;

            //query to check if that student did any homework
            $result_hw = mysqli_query($link, $hw_query);

                if ($result_hw == false) {
                    //he didn't do any homework
                    $avg_hw_score = 0; //which is zero}
                }
                
                $num_results = mysqli_num_rows($result_hw);
                    //get total of homework
                for ($i=0; $i<$num_results; $i++) {
                        $hw_rows = mysqli_fetch_assoc($result_hw);
                        $hw_total_score += $hw_rows['score'];
                }
                

                //get average of homework score
                $hw_avg_score = $hw_total_score / $num_results;
                //echo '<html><body>';
                //echo '<p>'.$hw_avg_score.'<p>';

                
                $course_data = mysqli_fetch_assoc($result);
                $final_total = 0;
                $avg_score_of_course = 0;

                //get from course data and calculate and store into variables
                $att_score = $course_data['attendance_score'] * PERCENTAGE_ATEENDANCE_SCORE;
                $avg_hw_score = $hw_avg_score * PERCENTAGE_AVG_HW_SCORE;
                $proj_score = $course_data['term_project_score'] * PERCENTAGE_PROJECT;
                $m_score = $course_data['midterm_score'] * PERCENTAGE_MIDTERM;
                $f_score = $course_data['final_score'] * PERCENTAGE_FINAL;

                $final_total = $attendance_score + $hw_avg_score + $project_score + $midterm_score + $final_score;
                $student_avg = number_format($final_total, 2, '.', ''); //format XX.XX
?>                                                 
<html>
<head>
        <title>Yuri's hw4</title>
        <meta name = "author" content="Yuri Van Steenburg" />
        <link rel= "stylesheet" type="text/css" href="style.css" />
</head>

    <body>
    <header>
        <h1> Student Record (Result from Request 2) </h1>
    </header>
        <main>
<div class = "student_ave_container">
<?php
            //prepares table. first row will be table headers
            echo '<table>
              <tr>
                <th>CWID</th>
                <th>Attendance</th>
                <th>Homework Average</th>
                <th>Project</th>
                <th>Midterm</th>
                <th>Final</th>
                <th>Overall Average</th>
            </tr>';
            echo '</table>';
            //prints student record
      //prints each column data into a row in the order that is seen here
            echo '<table>    
            <tr>
                  <td>'.$CWID.'</td>
                  <td>'.$att_score.'</td>
                  <td>'.$avg_hw_score.'</td>
                  <td>'.$proj_score.'</td>
                  <td>'.$m_score.'</td>
                  <td>'.$f_score.'</td>
                  <td>'.$student_avg.'</td>
              </tr>';
            echo '</table>';
         
?>
    </div>
    </main>
<?php

            mysqli_close($link); 

            echo '<h3>'.'The Student CWID:'.$CWID.' - Average Score of Course: '.$student_avg.'<h3>'; 
           }
    }
         
?>

<br>
<br>
<form action = 'index.php' method = 'LINK'>
	<input type = 'submit' value = 'Back'>
</form>

