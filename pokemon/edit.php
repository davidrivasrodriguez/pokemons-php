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
    header('Location: .');
    exit;
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $url = '.?op=editpokemon&result=noid';
    header('Location: ' . $url);
    exit;
}

$user = null;
if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
// if(($user === 'even' && $id % 2 != 0) ||
//     ($user === 'odd' && $id % 2 == 0)) {
//     header('Location: .');
//     exit;
// }

$sql = 'SELECT * FROM pokemon WHERE id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['id' => $id];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}

$sentence->execute();
$row = $sentence->fetch();
if($row == null) {
    echo 'No data found';
    exit;
}

$name = $weight = $height = $type_id = $evolution_count = '';
if(isset($_SESSION['old']['name'])) {
    $name = $_SESSION['old']['name'];
    unset($_SESSION['old']['name']);
} else {
    $name = $row['name'];
}
if(isset($_SESSION['old']['weight'])) {
    $weight = $_SESSION['old']['weight'];
    unset($_SESSION['old']['weight']);
} else {
    $weight = $row['weight'];
}
if(isset($_SESSION['old']['height'])) {
    $height = $_SESSION['old']['height'];
    unset($_SESSION['old']['height']);
} else {
    $height = $row['height'];
}
if(isset($_SESSION['old']['type_id'])) {
    $type_id = $_SESSION['old']['type_id'];
    unset($_SESSION['old']['type_id']);
} else {
    $type_id = $row['type_id'];
}
if(isset($_SESSION['old']['evolution_count'])) {
    $evolution_count = $_SESSION['old']['evolution_count'];
    unset($_SESSION['old']['evolution_count']);
} else {
    $evolution_count = $row['evolution_count'];
}

$sql = 'SELECT * FROM pokemon_types ORDER BY type';
$sentence = $connection->prepare($sql);
$sentence->execute();
$types = $sentence->fetchAll(PDO::FETCH_ASSOC);

$connection = null;
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Pokemon</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="..">Pokemon Management</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="..">Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./">Pokemon</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main">
            <div class="jumbotron">
                <div class="container">
                    <h4 class="display-4">Edit Pokemon</h4>
                </div>
            </div>
            <div class="container">
                <div>
                    <form action="update.php" method="post">
                        <div class="form-group">
                            <label for="name">Pokemon Name</label>
                            <input value="<?= $name ?>" required type="text" class="form-control" id="name" name="name" placeholder="Pokemon Name">
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight (kg)</label>
                            <input value="<?= $weight ?>" required type="number" step="0.01" class="form-control" id="weight" name="weight" placeholder="Weight">
                        </div>
                        <div class="form-group">
                            <label for="height">Height (m)</label>
                            <input value="<?= $height ?>" required type="number" step="0.01" class="form-control" id="height" name="height" placeholder="Height">
                        </div>
                        <div class="form-group">
                            <label for="type_id">Type</label>
                            <select required class="form-control" id="type_id" name="type_id">
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= $type['id'] ?>" <?= $type_id == $type['id'] ? 'selected' : '' ?>><?= $type['type'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="evolution_count">Evolution Count</label>
                            <input value="<?= $evolution_count ?>" required type="number" min="0" class="form-control" id="evolution_count" name="evolution_count" placeholder="Evolution Count">
                        </div>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>" />
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
                <hr>
            </div>
        </main>
        <footer class="container">
            <p>&copy; IZV 2024</p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>