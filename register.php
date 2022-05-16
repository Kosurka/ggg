<?php
require_once 'config.php';
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    $db = new PDO('pgsql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME, DB_USER, DB_PASS);
} catch (PDOException $e) {
    echo '{"error": {"text": "'.$e->getMessage().'"}}';
    die();
}


if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['email'])) {
    $login = $_POST['login'];
    $pas = $_POST['password'];
    $email = $_POST['email'];
    $sql = "select * from users";
    $sql2 = "insert into users (\"LOGIN\", \"PASSWORD\", \"EMAIL\") values ('$login', '$pas', '$email')";

    $count = $db->exec($sql2);

    if ($count == 1) {
        $response = [
            "response" => [
                "text" => "Вы успешно зарегистрировались"
            ]
        ];
    }
    else {
        $response = [
            "error" => [
                "text" => "Произошла ошибка! Попробуйте еще раз"
            ]
        ];
    }
}
else {
    $response = [
        "error" => [
            "text" => "Не передан логин/пароль"
        ]
    ];
}

echo json_encode($response);
?>