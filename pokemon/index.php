<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$user = null;
if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

try {
    $connection = new PDO(
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

$sql = 'SELECT p.*, t.type FROM pokemon p JOIN pokemon_types t ON p.type_id = t.id ORDER BY p.name, p.id';
try {
    $sentence = $connection->prepare($sql);
    if(!$sentence->execute()) {
        echo 'no sql';
        exit;
    }
} catch(PDOException $e) {
    echo '<pre>' . var_export($e, true) . '</pre>';
    exit;
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>pokemon management</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="..">pokemon management</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="..">home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./">pokemon</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main">
            <div class="jumbotron">
                <div class="container">
                    <h4 class="display-4">pokemon</h4>
                </div>
            </div>
            <div class="container">
                <?php
                if(isset($_GET['op']) && isset($_GET['result'])) {
                    if($_GET['result'] > 0) {
                        ?>
                        <div class="alert alert-primary" role="alert">
                            result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php 
                    } else {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php
                        }
                }
                ?>
                <div class="row">
                    <h3>pokemon list</h3>
                </div>
                <table class="table table-striped table-hover" id="tablaPokemon">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Weight</th>
                            <th>Height</th>
                            <th>Type</th>
                            <th>Evolution Count</th>
                            <?php
                            if(isset($_SESSION['user'])) {
                                ?>
                                <th>Delete</th>
                                <th>Edit</th>
                                <?php
                            }
                            ?>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($fila = $sentence->fetch()) {
                                ?>
                                <tr>
                                    <td><?php echo $fila['id']; ?></td>
                                    <td><?= $fila['name']; ?></td>
                                    <td><?= $fila['weight']; ?></td>
                                    <td><?= $fila['height']; ?></td>
                                    <td><?= $fila['type']; ?></td>
                                    <td><?= $fila['evolution_count']; ?></td>
                                    <td><a href="destroy.php?id=<?= $fila['id'] ?>" class="borrar">Delete</a></td>
                                    <td><a href="edit.php?id=<?= $fila['id'] ?>">Edit</a></td>
                                    <td><a href="show.php?id=<?= $fila['id'] ?>">View</a></td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
                <div class="row">
                    <?php
                    if(isset($_SESSION['user'])) {
                        ?>
                        <a href="create.php" class="btn btn-success">add pokemon</a>
                        <?php
                    }
                    ?>
                </div>
                <hr>
            </div>
        </main>
        <footer class="container">
            <p>&copy; IZV 2024</p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="js/script.js"></script>
    </body>
</html>