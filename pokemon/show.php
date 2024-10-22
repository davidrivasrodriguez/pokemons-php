<?php
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
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo 'no id';
    exit;
}
$sql = 'SELECT p.*, t.type FROM pokemon p JOIN pokemon_types t ON p.type_id = t.id WHERE p.id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['id' => $id];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}
if(!$sentence->execute()){
    echo 'no sql';
    exit;
}
$sentence->execute();
if(!$fila = $sentence->fetch()) {
    echo 'no data';
    exit;
}
$connection = null;
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>View Pokemon</title>
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
                    <h4 class="display-4">View Pokemon</h4>
                </div>
            </div>
            <div class="container">
                <div>
                    <div class="form-group">
                        <strong>Pokemon ID:</strong>
                        <?= $fila['id'] ?>
                    </div>
                    <div class="form-group">
                        <strong>Name:</strong>
                        <?= $fila['name'] ?>
                    </div>
                    <div class="form-group">
                        <strong>Weight:</strong>
                        <?= $fila['weight'] ?> kg
                    </div>
                    <div class="form-group">
                        <strong>Height:</strong>
                        <?= $fila['height'] ?> m
                    </div>
                    <div class="form-group">
                        <strong>Type:</strong>
                        <?= $fila['type'] ?>
                    </div>
                    <div class="form-group">
                        <strong>Evolution Count:</strong>
                        <?= $fila['evolution_count'] ?>
                    </div>
                    <div class="form-group">
                        <a href="./" class="btn btn-primary">Back to List</a>
                    </div>
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