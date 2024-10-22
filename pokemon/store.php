<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
        )
    );
} catch(PDOException $e) {
    header('Location: create.php?op=errorconnection&result=0');
    exit;
}

$resultado = 0;
$url = 'create.php?op=insertpokemon&result=' . $resultado;

// Inicializar variables
$name = $weight = $height = $type_id = $evolution_count = '';

if (isset($_POST['name'], $_POST['weight'], $_POST['height'], $_POST['type_id'], $_POST['evolution_count'])) {
    $name = trim($_POST['name']);
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $type_id = $_POST['type_id'];
    $evolution_count = $_POST['evolution_count'];
    
    $ok = true;

    // Validaciones
    if (strlen($name) < 2 || strlen($name) > 100) $ok = false;
    if (!(is_numeric($weight) && $weight > 0 && $weight <= 1000)) $ok = false;
    if (!(is_numeric($height) && $height > 0 && $height <= 15)) $ok = false;
    if (!(is_numeric($type_id) && $type_id > 0)) $ok = false;
    if (!(is_numeric($evolution_count) && $evolution_count >= 0)) $ok = false;

    if ($ok) {
        $sql = 'INSERT INTO pokemon (name, weight, height, type_id, evolution_count) VALUES (:name, :weight, :height, :type_id, :evolution_count)';
        $sentence = $connection->prepare($sql);
        $parameters = ['name' => $name, 'weight' => $weight, 'height' => $height, 'type_id' => $type_id, 'evolution_count' => $evolution_count];

        foreach ($parameters as $nombreParametro => $valorParametro) {
            $sentence->bindValue($nombreParametro, $valorParametro);
        }

        try {
            $sentence->execute();
            $resultado = $connection->lastInsertId();
            $url = 'index.php?op=insertpokemon&result=' . $resultado;
        } catch (PDOException $e) {
            echo 'Error al insertar: ' . $e->getMessage();
            error_log($e->getMessage()); // Registra el error
        }
    }
}

// Guardar los valores en la sesión si la inserción falla
if ($resultado == 0) {
    $_SESSION['old'] = [
        'name' => $name,
        'weight' => $weight,
        'height' => $height,
        'type_id' => $type_id,
        'evolution_count' => $evolution_count
    ];
}

header('Location: ' . $url);
exit;
?>