

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">


<style>
.checked {
  color: orange;
}
.starc{
 font-size: 30px;
 padding: 5px;
 cursor: pointer;
}
form{
    padding: 5px;
    
}
#feedback_dis{
    padding: 5px;
    font-weight: 500;
    font-size: 15px;
    width: 100%;
    height: 4rem;
    margin: 5px 0px;
    word-spacing: 2px;
    letter-spacing: 3px;
}
.heading {
  font-size: 25px;
  margin-right: 25px;
}

.fa {
  font-size: 25px;
}

.checked {
  color: orange;
}

/* Three column layout */
.side {
  float: left;
  width: 15%;
  margin-top:10px;
}

.middle {
  margin-top:10px;
  float: left;
  width: 70%;
}

/* Place text to the right */
.right {
  text-align: right;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* The bar container */
.bar-container {
  width: 100%;
  background-color: #f1f1f1;
  text-align: center;
  color: white;
}

/* Individual bars */
.bar-5 { height: 18px; background-color: #04AA6D;}
.bar-4 {height: 18px; background-color: #2196F3;}
.bar-3 { height: 18px; background-color: #00bcd4;}
.bar-2 {height: 18px; background-color: #ff9800;}
.bar-1 { height: 18px; background-color: #f44336;}

/* Responsive layout - make the columns stack on top of each other instead of next to each other */
@media (max-width: 400px) {
  .side, .middle {
    width: 100%;
  }
  .right {
    display: none;
  }
}
.feed-container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
}

.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.feed-container ::after {
  content: "";
  clear: both;
  display: table;
}

.feed-container  img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.feed-container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}
</style>
</head>
<body>
<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "shopping_db";
$con = mysqli_connect($server, $user, $password, $db);
$p_id = $_GET['pid'];
if (isset($_SESSION["user_role"])) {
    $query = "SELECT * FROM feedback where fb_uid=" . $_SESSION["user_id"]." and fb_pid=".$p_id;
    $result = mysqli_query($GLOBALS['con'], $query);
    if ($result) {
        $row = mysqli_num_rows($result);
        if ($row > 0) {
            $sql =
                "SELECT fb_uname,fb_uid,fb_pid,fb_rating,fb_dis from feedback where fb_uid=" .
                $_SESSION["user_id"]." and fb_pid=".$p_id;
            $result = $GLOBALS['con']->query($sql);
            while ($row = $result->fetch_assoc()) {
                $mainhtmltxt =
                    '
                <h3>You already rate this product</h3>
                <div class="feed-container text-left">
                <img src="./images//user.png" alt="user" style="width:100%;">
                <p class="text-capitalize">' .
                    $row["fb_dis"] .
                    '</p>
                <div style="display: flex; justify-content: space-evenly;">
                  <p>' .
                    $row["fb_uname"] .
                    '</p>
                  <p><?php echo "dfg"; ?>' .
                    $row["fb_rating"] .
                    ' STARS</p>
                </div>
                </div>
                ';
                echo $mainhtmltxt;
            }
        } else {
            if (array_key_exists('submitfeedback', $_POST)) {
                if (isset($_SESSION["user_role"])) {
                    submitfeedbackfun();
                }
            }
            $prints = " <html>
                    <h3>Rate This product</h3>
                    <hr>
<form method='POST'>
<span id='star1' class='fa fa-star starc'></span>
<span id='star2' class='fa fa-star starc'></span>
<span id='star3' class='fa fa-star starc'></span>
<span id='star4' class='fa fa-star starc'></span>
<span id='star5' class='fa fa-star starc'></span><br><br>
<input type='text' placeholder='Write something about your experiance' name='feedback_dis' id='feedback_dis' required>
<input type='text' placeholder='Enter' name='rate_user' id='rate_user' readonly style='display: none;'>

<input type='submit' name='submitfeedback'
                class='button btn btn-success btn-block text-capitalize' value='submit' />

</form>
                </html>";
            echo "$prints";
        }
    }
} else {
    $prints = " <html>
        <h3>Login first to rate this product</h3>
    </html>";
    echo "$prints";
}

function submitfeedbackfun()
{
    $p_id = $_GET['pid'];
    $query = "SELECT * FROM feedback where fb_uid=" . $_SESSION["user_id"]. " and fb_pid=".$p_id;
    $result = mysqli_query($GLOBALS['con'], $query);
    if ($result) {
        $row = mysqli_num_rows($result);
        if ($row == 0) {
            $feedback_dis = mysqli_real_escape_string(
                $GLOBALS['con'],
                $_POST["feedback_dis"]
            );
            $rate_user = mysqli_real_escape_string(
                $GLOBALS['con'],
                $_POST["rate_user"]
            );
            
            if (isset($_SESSION["user_role"])) {
                $params1 = [
                    'fb_uname' => $_SESSION["username"],
                    'fb_uid' => $_SESSION["user_id"],
                    'fb_pid' => $p_id,
                    'fb_rating' => $rate_user,
                    'fb_dis' => $feedback_dis,
                ];
                $db = new Database();
                $db->insert('feedback', $params1);
            } else {
                //
                $prints = " <html>
                  <h3>Login first to rate this product</h3>
              </html>";
                echo "$prints";
            }
        } else {
            //
        }
        mysqli_free_result($result);
    }
}
?>


<div class="row">
<div class="col-lg-6">
        <h3>Ratings</h3>
        <?php
        $onestar = 0;
        $twostar = 0;
        $threestar = 0;
        $fourstar = 0;
        $fivestar = 0;
        $total = 0;
        $totalpeople = 0;
        $mainhtmltxt = '';
        $p_id = $_GET['pid'];
        $sql = "SELECT fb_uname,fb_uid,fb_pid,fb_rating,fb_dis from feedback where fb_pid=".$p_id;
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $total = $total + $row["fb_rating"];
                $totalpeople += 1;
                if ($row["fb_rating"] == 1) {
                    $GLOBALS['onestar'] += 1;
                } elseif ($row["fb_rating"] == 2) {
                    $GLOBALS['twostar'] += 1;
                } elseif ($row["fb_rating"] == 3) {
                    $GLOBALS['threestar'] += 1;
                } elseif ($row["fb_rating"] == 4) {
                    $GLOBALS['fourstar'] += 1;
                } elseif ($row["fb_rating"] == 5) {
                    $GLOBALS['fivestar'] += 1;
                }

                $mainhtmltxt =
                    '
<div class="feed-container text-left">
<img src="./images//user.png" alt="user" style="width:100%;">
<p class="text-capitalize">' .
                    $row["fb_dis"] .
                    '</p>
<div style="display: flex; justify-content: space-evenly;">
  <p>' .
                    $row["fb_uname"] .
                    '</p>
  <p>' .
                    $row["fb_rating"] .
                    ' STARS</p>
</div>
</div>
';
                echo $mainhtmltxt;
            }
        } else {
            echo "No rating be the first one";
        }
        $avgrat=0;
        if($total>0){
            if($totalpeople>0){
                $avgrat = intval($total / $totalpeople);
            }
        }
        $con->close();

// echo $mainhtmltxt;
?>
    </div>





    <div class="col-lg-6" >
    
<span class="heading">User Rating</span>
<?php
 for($i=0;$i<$avgrat;$i++){
     echo "<span class='fa fa-star checked'></span>";
 }
 for($i=0;$i<5-$avgrat;$i++){
    echo "<span class='fa fa-star'></span>";
}
?>

<p><?php echo $avgrat; ?> average based on <?php echo $totalpeople; ?> reviews.</p>
<hr style="border:3px solid #f1f1f1">

<div class="row">
  <div class="side">
    <div>5 star</div>
  </div>
  <div class="middle">
    <div class="bar-container">
      <div class="bar-5" style="width: <?php echo intval(
          ($fivestar / $totalpeople) * 100
      ); ?>%;"></div>
    </div>
  </div>
  <div class="side right">
    <div><?php echo $fivestar; ?></div>
  </div>
  <div class="side">
    <div>4 star</div>
  </div>
  <div class="middle">
    <div class="bar-container">
      <div class="bar-4"style="width: <?php echo intval(
          ($fourstar / $totalpeople) * 100
      ); ?>%;"></div>
    </div>
  </div>
  <div class="side right">
    <div><?php echo $fourstar; ?></div>
  </div>
  <div class="side">
    <div>3 star</div>
  </div>
  <div class="middle">
    <div class="bar-container">
      <div class="bar-3" style="width: <?php echo intval(
          ($threestar / $totalpeople) * 100
      ); ?>%;"></div>
    </div>
  </div>
  <div class="side right">
    <div><?php echo $threestar; ?></div>
  </div>
  <div class="side">
    <div>2 star</div>
  </div>
  <div class="middle">
    <div class="bar-container">
      <div class="bar-2" style="width: <?php echo intval(
          ($twostar / $totalpeople) * 100
      ); ?>%;"></div>
    </div>
  </div>
  <div class="side right">
    <div><?php echo $twostar; ?></div>
  </div>
  <div class="side">
    <div>1 star</div>
  </div>
  <div class="middle">
    <div class="bar-container">
      <div class="bar-1" style="width: <?php echo intval(
          ($onestar / $totalpeople) * 100
      ); ?>%;"></div>
    </div>
  </div>
  <div class="side right">
    <div><?php echo $onestar; ?></div>
  </div>
</div>

    </div>
</div>
</body>

<script>
let cos=0;
document.getElementById("star1").onmouseover = function(){
    cos=1;
    colorstars(1)
};
document.getElementById("star2").onmouseover = function(){
    cos=2;
    colorstars(2)
};
document.getElementById("star3").onmouseover = function(){
    cos=3;
    colorstars(3)
};
document.getElementById("star4").onmouseover = function(){
    cos=4;
    colorstars(4)
};
document.getElementById("star5").onmouseover = function(){
    cos=5;
    colorstars(5)
};

function colorstars(c){
    for (let i=c;i<=5;i++){
        document.getElementById("star"+i).classList.remove("checked")
    }
    for (let i=1;i<=c;i++){
        document.getElementById("star"+i).classList.add("checked")
    }
    document.getElementById("rate_user").value=cos;
    // console.log(cos)
}

</script>


</html>

