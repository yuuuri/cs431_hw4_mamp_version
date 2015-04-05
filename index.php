<?php
    session_start();
?>

<!-- This program works with index.php, view_avg_for_student.php, and tests_stats.php-->

<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Yuri's hw4</title>
        <meta name = "author" content="Yuri Van Steenburg" />
        <link rel= "stylesheet" type="text/css" href="style.css" />
    </head>
    <body>

        <header>
        </header>

        <main>
            <div class = "top_container">
            <h1> Student Records </h1>

            <?php
                
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
                    echo "Error: Could not connect to data base.  Try again\n";
                    exit;
                } 
                //get all students CWID
                $query_cwid = 'SELECT cwid FROM student';
                $result_cwids = $link->query($query_cwid);

                //if rows == 0, error, means no result came out
                $num_result_cwids = mysqli_num_rows($result_cwids);
                if ($num_result_cwids === 0) {
                    echo '<p>returned no row from query! existing the program</p>';
                    exit;
                } else {
                    //format output using html to display data in a table format
				            //formats the table with 1 pixel thick, black colored borders
                    /*echo  '<style >
                      table, th, td
                        {
                            border:1px solid black;
                        }
                    </style>';*/
    


                    //prepares table
                    echo '<table>
					          <tr>
						            <th>Student CWID</th>
        					  </tr>';

                    //while statement to continue printing all the data into table until exhausted
                    //the "$rows = mysqli_fetch_array($prof_query)" statement 
                    //basically returns the number of rows that the query has.
				            while($rows = mysqli_fetch_array($result_cwids))
			              {
				            	  //prints each column data into a row in the order that is seen here
				                echo '<tr>
                            <td>'.$rows['cwid'].'</td>
					              </tr>';
				            }
				            echo '</table>';
                }
                //$result_cwids->free();
                mysqli_close($link);
            ?>
            </div>
            <div class = "mid_container">
                <h2>View Average Score For A Given Student</h2>
                <form action="view_avg_for_student.php" method="POST">
                        <b>Enter CWID and click submit: </b> 
                        <input type="text" name="cwid" maxlength = "9">
                        <input type="submit">
                </form>
                <?php
                    if(isset($_SESSION['message'])){
                        echo '<font color = "red"><i>'.$_SESSION['message'].'</i></font>';
                    }
                    unset($_SESSION['message']); // clear the value so that it doesn't display again
                ?>



            </div>

            <div class = "bottom_container">
                <h2>Average and Standard Deviation of midterm and final scores</h2>
                <form action = "tests_stats.php" method="post">
                    <input type = "submit" id = "stats_submit" />
                </form>            
            </div>
        </main>


        <footer>
        </footer>

    </body>
</html>

