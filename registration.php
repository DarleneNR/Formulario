<?php

// Asegurar que el nombre en el campo incluide, esté el nombre del archivo de conexión a la BD
include('connection.php');
session_start();

if (isset($_POST['register'])) {

    // Aqui se está captando los datos ingresados en los campos mencionados
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Encriptación de contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Aqui se consulta si el email existe en la DB 
    $query = $connection->prepare("SELECT * FROM users WHERE EMAIL=:email");
    $query->bindParam("email", $email, PDO::PARAM_STR);
    $query->execute();

    // Si el email existe entonces se muestra el mensaje de error
    if ($query->rowCount() > 0) {
        echo '<p class="error">The email address is already registered!</p>';
    }

    // Si el email no existe entonces se muestra el mensaje 
    if ($query->rowCount() == 0) {
        $query = $connection->prepare("INSERT INTO users(USERNAME,PASSWORD,EMAIL) VALUES (:username,:password_hash,:email)");
        $query->bindParam("username", $username, PDO::PARAM_STR);
        $query->bindParam("password_hash", $password_hash, PDO::PARAM_STR);
        $query->bindParam("email", $email, PDO::PARAM_STR);

        $result = $query->execute();

        if ($result) {
            echo '<p class="success">Your registration was successful!</p>';
        } else {
            echo '<p class="error">Something went wrong!</p>';
        }
    }
}

?>