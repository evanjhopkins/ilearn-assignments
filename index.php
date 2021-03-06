<?php
session_start();
?>
<html>
<head>
</head>
<link rel="stylesheet" href="css.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css">
<script src="https://code.jquery.com/jquery-2.1.3.min.js"</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<body>

<nav class="navbar navbar-custom">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Marist iLearn</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<!--       <ul class="nav navbar-nav">
        <li><a href="index.php">About</a></li>
         <li class="active"><a href="#">Assignments<span class="sr-only">(current)</span></a></li>
      </ul> -->
      <ul class="nav navbar-nav navbar-right">
        <li><a href="https://github.com/evanjhopkins/ilearn-assignments">Source</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


<?php

if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password']) ){
  $url = 'http://evanjhopkins.com/ilearn-assignments/phyth/phyth.php';
  $options = array(
      'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'POST',
          'content' => http_build_query(array("function"=>"ilearn", "username"=>$_POST['username'], "password"=>$_POST['password'])),
      ),
  );
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  $result = json_decode($result);
  if(isset($result->error) && !empty($result->error)){
    echo "<div class='alert alert-danger' role='alert'>$result->error Your login credentials were likely incorrect, or my code just sucks</div>";
  }

  ?><table class="table">
  <?php
  foreach ($result->data as $aclass){
    echo "<tr bgcolor='#D1D1D1'><th>$aclass->name</th><th>Status</th> <th>Open Date</th><th>Due Date</th></tr>";
    foreach ($aclass->assignments as $assignment){
        $dtime = DateTime::createFromFormat("Y-m-d H:i:s", $assignment->dueDate);
        $dueStamp = $dtime->getTimestamp();
        if($assignment->status=="Not Started"){
          //if not started and due, highlight row
          if($dueStamp < strtotime('3 days')){
            //if due in next 3 days, highlight in red
            echo "<tr bgcolor='#F2DEDE'>";
          }else{
            //otherwise highlight in yellow
            echo "<tr bgcolor='#FCF8E3'>";
          }  
        }else{
          echo "<tr>";
        }
        echo "<td>&nbsp;&nbsp;&nbsp; $assignment->title </td>";
        echo "<td> $assignment->status </td>";
        echo "<td> ".substr($assignment->openDate,0, 16)."</td>";
        echo "<td> ".substr($assignment->dueDate,0,16)." </td>";
      echo "</tr>";
    }
  }
?></table><?php
}else{?>
  <div class="login">
  <form action="" method="post">
    <input type="text" name="username" placeholder="Username"/>
    <input type="password"name="password" placeholder="Password"/>
    <button class="btn btn-success has-spinner">
      <span class="spinner"><i class="icon-spin icon-refresh"></i></span>
      Login
    </button>
  </form>
  </div>
  <div class="overview">
  &nbsp;&nbsp;&nbsp;&nbsp;The goal of this project is to provide Marist students with an easy way to manage their assigments. 
  All assignments are listed on a single page under the header of their class title.
  Assignments due within the week are highlighted in yellow.
  Assignments due within the next two days are highlighted in red.
  Below you will find a screenshot of the app in action.
  To get started simply log in with your Marist account.
  <a href="http://i.imgur.com/n8MRit6.png">
    <img class="demo-img"src="http://i.imgur.com/n8MRit6.png">
  </a>
  </div>
<?php } ?>
</body>
</html>
<script>
$(function(){
    //using js until tweaking is done
    $('.navbar').css({'margin-bottom':'0px'});
    
    $('a, button').click(function() {
        $(this).toggleClass('active');
    });
});
</script>