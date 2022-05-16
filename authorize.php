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


//если передали логин пароль
if (!empty($_POST['login']) || !empty($_POST['password'])) {
    $login = $_POST['login'];
    $pas = $_POST['password'];


    $sql = "select * from users where \"LOGIN\" = '$login' and \"PASSWORD\" = '$pas'";
    $user = $db->query($sql)->fetch();

    // такой пользователь есть
    if (!empty($user)) {
        $id = $user['ID'];
        $email = $user ['EMAIL'];
        $login = $user ['LOGIN'];
        // сгенирируем новый токен для пользователя
        $token = md5(time());
        // время действия токена на 24 часа
        $expiration = date("Y-m-d H:i:s", time() + 24 * 60 * 60);
        $sql_upd = "update users SET \"TOKEN\" = '$token', \"EXPIRATION\" = '$expiration' where \"ID\" = '$id'";
        $stmt = $db->exec($sql_upd);
        if ($stmt == 1) {
            echo json_encode([
                "response" => [
                    "text" => "Авторизация прошла успешна!"
                ],
                "user" => [
                    "token" => $token,
                    "id" => $id
                ]
            ]);
        } else {
            echo json_encode([
                "response" => [
                    "error" => "Что-то пошло не так! Попробуйте еще раз"
                ]
            ]);
        }
    } else {
        echo json_encode([
            "response" => [
                "error" => "Проверьте введенные данные!"
            ]
        ]);
    }
}
else {
    echo json_encode([
        "response" => [
            "error" => "Не передан логин/пароль"
        ]
    ]);
}
?>