<?php
session_start();
?>
<html>
<head>
</head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Pretty iLearn</a>
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

if(isset($_POST['username']) && isset($_POST['password'])){
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
    <input type="submit">
  </form>
  </div>
<?php } ?>
</body>
</html>