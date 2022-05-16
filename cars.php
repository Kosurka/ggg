<?php
require_once 'config.php';
header('Content-Type: application/json; Charset: UTF-8;');

error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    $db = new PDO('pgsql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME, DB_USER, DB_PASS);
} catch (PDOException $e) {
    echo '{"error": {"text": "'.$e->getMessage().'"}}';
    die();
}

if (isset($_GET['token']) && isset($_GET['id'])) {
    $token = $_GET['token'];
    $id = $_GET['id'];

    // Проверка токена
    // $sql = sprintf ('SELECT "ID" FROM "users" WHERE "TOKEN" LIKE '%s' AND "EXPIRATION" > CURRENT_TIMESTAMP', $token);
    $sql = "select * from users where \"ID\" = '$id' and \"TOKEN\" = '$token'";
    $user = $db->query($sql)->fetch();

    if (empty($user['ID']) || empty($user['TOKEN'] || empty($user['EXPIRATION']))) {
        echo json_encode([
            "response" => [
                "error" => "Ошибка авторизации"
            ]
        ]);
        return;
    }

    //check token expiration time
    $unixTokenExpiration = strtotime($user['EXPIRATION']);

    if ($unixTokenExpiration < time()) {
        echo json_encode([
            "response" => [
                "error" => "Время авторизации истекло. Пройдите её заново!"
            ]
        ]);
        return;
    }

    $sql = "select * from cars";
    $cars = $db->query($sql)->fetchAll();
    echo json_encode($cars);
}
else {
    echo json_encode([
        "response" => [
            "error" => "Ошибка авторизации"
        ]
    ]);
}

?>