<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if(!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}
try {
    $connection = new \PDO(
      'mysql:host=localhost;dbname=pokemondatabase',
      'pokemonuser',
      'pokemonpassword',
      array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
} catch(PDOException $e) {
    echo 'no connection';
    exit;
}
if(isset($_POST['name'])) {
    $name = $_POST['name'];
} else {
    echo 'no name';
    exit;
}
if(isset($_POST['weight'])) {
    $weight = $_POST['weight'];
} else {
    echo 'no weight';
    exit;
}
if(isset($_POST['height'])) {
    $height = $_POST['height'];
} else {
    echo 'no height';
    exit;
}
if(isset($_POST['type_id'])) {
    $type_id = $_POST['type_id'];
} else {
    echo 'no type';
    exit;
}
if(isset($_POST['evolution_count'])) {
    $evolution_count = $_POST['evolution_count'];
} else {
    echo 'no evolution count';
    exit;
}
if(isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    echo 'no id';
    exit;
}

// if(($user === 'even' && $id % 2 != 0) ||
//     ($user === 'odd' && $id % 2 == 0)) {
//     header('Location: .');
//     exit;
// }
$sql = 'UPDATE pokemon SET name = :name, weight = :weight, height = :height, type_id = :type_id, evolution_count = :evolution_count WHERE id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['name' => $name, 'weight' => $weight, 'height' => $height, 'type_id' => $type_id, 'evolution_count' => $evolution_count, 'id' => $id];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}

try {
    $sentence->execute();
    $resultado = $sentence->rowCount();
    $url = '.?op=editpokemon&result=' . $resultado;
} catch(PDOException $e) {
    $resultado = 0;
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['weight'] = $weight;
    $_SESSION['old']['height'] = $height;
    $_SESSION['old']['type_id'] = $type_id;
    $_SESSION['old']['evolution_count'] = $evolution_count;
    $url = 'edit.php?id=' . $id . '&op=editpokemon&result=' . $resultado;
}

header('Location: ' . $url);