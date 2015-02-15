<html>
<head>
</head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Brand</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php">About</a></li>
         <li class="active"><a href="#">Assignments<span class="sr-only">(current)</span></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">About</a></li>
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
      echo "<tr>";
        echo "<td>&nbsp;&nbsp;&nbsp; $assignment->title </td>";
        echo "<td> $assignment->status </td>";
        echo "<td> $assignment->openDate </td>";
        echo "<td> $assignment->dueDate </td>";
      echo "</tr>";
    }
  }
?></table><?php
}else{?>
  <form action="" method="post">
    <input type="text" name="username" placeholder="Username"/>
    <input type="password"name="password" placeholder="Password"/>
    <input type="submit">
  </form>
<?php } ?>
</body>
</html>