<?php 


$app->post("/auth/register", function ($req, $res) {
    $user = $req->body()["user"];
    $password = password_hash($req->body()["password"], PASSWORD_DEFAULT );
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
    $res->status(201);
});

$app->post("/auth/login", function ($req, $res) {
    $user = $req->body()["user"];
    $db = new SQLite3('database.db');
    $query = "SELECT * FROM users WHERE user = :username";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $user, SQLITE3_TEXT);
    $result = $stmt->execute();
    if ($row = $result->fetchArray()) {
     
        $password = "password123"; 
    
        if (password_verify($req->body()["password"], $row['password'])) {
            $_SESSION['user'] = $row['user'];
            echo "Login successful!";
       
        } else {
  
            echo "Invalid password!";
          
        }
    } else {
   
        echo "User not found!";
 
    }
    $db->close();
});