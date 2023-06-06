<?php 


$app->post("/auth/register", function ($req, $res) {
    try {
        $user = $req->body()["user"];
        $password = password_hash($req->body()["password"], PASSWORD_DEFAULT);
        $db = new SQLite3('database.db');
        
        $query = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL
        )";
        $db->exec($query);
        
        $query = "INSERT INTO users (user, password) VALUES ('$user', '$password')";
        $db->exec($query);
        
        $db->close();
        $res->status(301);
        $res->redirect("/login");
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $res->status(500);
        $res->send("An error occurred: $error_message");
    }
});

$app->post("/auth/login", function ($req, $res) {
    $user = $req->body()["user"];
    $db = new SQLite3('database.db');
    $query = "SELECT * FROM users WHERE user = :username";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $user, SQLITE3_TEXT);
    $result = $stmt->execute();
    if ($row = $result->fetchArray()) {
        if (password_verify($req->body()["password"], $row['password'])) {
            $_SESSION['user'] = $row['user'];
            $_SESSION['user_id'] = 1;
            $res->status(301);
            $res->redirect("/");
       
        } else {
  
            echo "Invalid password!";
          
        }
    } else {
   
        echo "User not found!";
 
    }
    $db->close();
});