<?php

function writeSnippetDatabase($data)
{
    $date = $data["date"];
    $user = $data["user"];
    $userId = $data["user_id"];
    $text = $data["text"];
    $code = $data["code"];
    $language = $data["language"];

    try {
        $db = new SQLite3('database.db');
        $query = "CREATE TABLE IF NOT EXISTS snippets (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            date TEXT NOT NULL ,
            text TEXT  NOT NULL,
            code TEXT NOT NULL,
            language TEXT NOT NULL,
            user TEXT  NOT NULL UNIQUE,
            user_id TEXT  NOT NULL UNIQUE
        )";
        $db->exec($query);
        $query = "INSERT INTO snippets ( date, text, code, language, user, user_id) VALUES (
            '$date',
            '$text',
            '$code',
            '$language',
            '$user',
            '$userId'
        )";
        $db->exec($query);
        $db->close();

        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('database.json', $json);

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        die("An error occurred: $error_message");
    }
}

function readSnippetDatabase()
{
    try {
        $db = new SQLite3('database.db');
        $query = "SELECT * FROM snippets";
        $result = $db->query($query);

        $data = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }

        $db->close();

        return $data;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        die("An error occurred: $error_message");
    }
}