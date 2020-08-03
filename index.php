<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div>
  <h2> Завантажити CSV файл з данними</h2>
  <form method="post" enctype="multipart/form-data">
    <label for="file">Завантажте CSV</label>
    <br>
    <input id="file" type="file" name="csv">
    <br>
    <button>Завантажити</button>
  </form>
</div> 

<?php
  $mysqli = new mysqli('localhost', 'root', '', 'test_db');
  if ($mysqli -> connect_errno) {
    echo "Faild to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }
  
  if ($_FILES) {
    if ($_FILES['csv']['type'] != 'application/vnd.ms-excel' || $_FILES['csv']['type'] == '') {
      echo "Помилка! Даний файл не вірного формату.";
    } else {
      if(move_uploaded_file($_FILES['csv']['tmp_name'], $_FILES['csv']['name'])) {
        if (($_FILES['csv']['size']) > 1) {
          echo "Файл валідний, і був успішно завантажений.";
        }
        $file = fopen($_FILES['csv']['name'], 'r');
        while (!feof($file)) { 
      $arr = fgetcsv($file, 1024, ';');
      $j = count($arr);
      if ($j > 1) {
      $mysqli->query("INSERT INTO tab (firstName, lastName, birthDay, dateChange, description) VALUES ('{$arr[0]}', '{$arr[1]}', '{$arr[2]}', '{$arr[3]}', '{$arr[4]}')");
    } elseif (($_FILES['csv']['size']) == 0) {
      $mysqli->query( "TRUNCATE TABLE tab" );
      echo "Таблиця бази даних була очищена!";
    }
  }
        }
        fclose($file);
      }
    }
  $query ="SELECT * FROM tab";
  $result = mysqli_query($mysqli, $query) or die("Ошибка " . mysqli_error($mysqli)); 
  if($result) {
    $rows = mysqli_num_rows($result);
    echo "<table cellspacing = '0' border = '1' width = '500'><tr align = 'center'><th>uid</th><th>firstName</th><th>lastName</th><th>birthDay</th><th>dateChange</th><th>description</th></tr>";
    for ($i = 0 ; $i < $rows ; ++$i)
    {
        $row = mysqli_fetch_row($result);
        echo "<tr align = 'center'>";
            for ($j = 0 ; $j < 6 ; ++$j) echo "<td>$row[$j]</td>";
        echo "</tr>";
    }
    echo "</table>";
  }
  
  $mysqli->close();

?>
</body>
</html>
