<?php

// Asegurar que el nombre en el campo incluide, esté el nombre del archivo de conexión a la BD
include('connection.php');
session_start();

// Se captura el username y password
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Se busca si el usernmame existe en la BD por medio del script SQL 
    $query = $connection->prepare("SELECT * FROM users WHERE USERNAME=:username");
    $query->bindParam("username", $username, PDO::PARAM_STR);
    $query->execute();

    // Se guarda todo lo encontrado en la BD en result
    $result = $query->fetch(PDO::FETCH_ASSOC);

    // Si no hay datos entrege el mensaje de error
    if (!$result) {
        echo '<p class="error">Username password combination is wrong!</p>';
    
    // Si encuentra datos, entonces:
    } else {

        // Si el password ingresado coincide con el username entonces muestra mensaje
        // Las variables en parámetro deben ser igual (Mayúscula o Minúcula) a las variables que se capturan al inicio del doc o en BD
        if (password_verify($password, $result['password'])) {
            $_SESSION['user_id'] = $result['id'];
            echo '<p class="success">Congratulations, you are logged in!</p>';
        
        // Si el password ingresado no coincide con el username entonces muestra mensaje de error
        } else {
            echo '<p class="error">Username or password combination is wrong!</p>';
        }
    }
}

?>