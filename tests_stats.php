<?php
 session_start();
/*
 * 
 *
 *
 *
 *
 * */

class students_rec {

    private $midterm_arr = array();
    private $final_arr = array();
    private $query_result;
    private $ave_midterm;
    private $ave_final;
    private $sd_midterm;
    private $sd_final;
    public $conn;

    public function __construct() {

        //local variables to connect to database
        $user = 'root';
        $password = 'root';
        $db = 'hw3';
        $host = '127.0.0.1';
        $port = 8889;
        $socket = 'localhost:/Applications/MAMP/tmp/mysql/mysql.sock';

        //link to database

        $link = mysqli_init();
        $success = mysqli_real_connect($link,$host,$user,$password,$db,$port,$socke);
        if (mysqli_connect_errno()) {
            echo "Error: Could not connect to data base.  Try again\n";
            exit;
        }
        $this->conn = $link;
    }


    public function get_query_result() {
        //query for get everybody's midterm & final scores
        $query = 'SELECT midterm_score, final_score FROM course_score';

        $result = mysqli_query($this->conn, $query);
        if($result == false) {
            echo 'query returned false, exiting the program...';
            exit;
        }

        //get how many rows that data contains
        $row_result = mysqli_num_rows($result);
        if ($row_result === 0) {
            echo 'returned no row!';
            exit;
        }

        $this->query_result = $result;
    }

    public function get_averages() { //creates midterm_arr and final_arr for calc sd
        $row_result = mysqli_num_rows($this->query_result);

        for ($i=0; $i<$row_result; $i++){
            //fetch data
            $rows = mysqli_fetch_assoc($this->query_result);

            //midterm arrays and midterm total
            $this->midterm_arr[$i] = $rows['midterm_score'];
            $total_midterm += $rows['midterm_score'];

            //final arrays and final total
            $this->final_arr[$i] = $rows['final_score'];
            $total_final += $rows['final_score'];
        }
        $this->ave_midterm = ($total_midterm/$row_result);
        $this->ave_final = ($total_final/$row_result);
    }


    public function print_results() {
        echo '<p>'.' ********************   Results   ******************** '.'</p>';
        $midterm_avg = number_format($this->ave_midterm, 2, '.', '');
        echo '<p>'.'*****   '.' MIDTERM AVERAGE = '.$midterm_avg.'</p>';

        $final_avg = number_format($this->ave_final, 2, '.', '');
        echo '<p>'.'*****  '.' FINAL AVERAGE = '.$final_avg.'</p>';

        $midterm_sd = number_format($this->sd_midterm, 2, '.', '');       
        echo '<p>'.'*****   '.' MIDTERM STANDARD DEVIATION = '.$midterm_sd.'</p>';

        $final_sd = number_format($this->sd_final, 2, '.', '');
        echo '<p>'.' *****  '.' Final STANDARD DEVIATION = '.$final_sd.'</p>';

    }

    // function reuse from homework #1
    function get_standard_deviations() {
          $d = $this->midterm_arr;
          $carry = 0.0;
          $n = count($d);

          foreach($d AS $index=>$value) {
                $d[$index] = ((double)$value) - $this->ave_midterm;
          }

          foreach($d as &$value) {
            $value = $value * $value;
            $carry = $carry + $value; 
            //echo "each carry is ".$carry."\n";
          }
          $this->sd_midterm = sqrt($carry /((int)($n-1)));

         //for final
          $d = $this->final_arr;

          foreach($d AS $index=>$value) {
                $d[$index] = ((double)$value) - $this->ave_final;
          }

          foreach($d as &$value) {
            $value = $value * $value;
            $carry = $carry + $value; 
            //echo "each carry is ".$carry."\n";
          }
          $this->sd_final = sqrt($carry /((int)($n-1))); 
    }

} // end of class


    $student_rec = new students_rec();



?>

<!DOCTYPE html>
<html>
    <head>
        <title>Average and Standard Deviation of Midterm & Final</title>
        <meta name = "author" content="Yuri Van Steenburg" />
        <link rel= "stylesheet" type="text/css" href="style.css" />
    </head>
    
    <body>
        <main>

            <h2>Average and Standard Deviation of Midterm & Final</h2>

            <div class = "stats_container">

                <?php
                    $student_rec->get_query_result();
                    $student_rec->get_averages();
                    $student_rec->get_standard_deviations();
                    $student_rec->print_results();

                ?>
            
            </div>
            <div id = "stats_bottom_container">
                <br>
                <br>
                    <form action = 'index.php' method = 'LINK'>
	                      <input type = 'submit' value = 'Back'>
                    </form>

            </div>
        <main>
    </body>
</html>




