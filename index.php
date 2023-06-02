<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once "utils.php";
require_once "db.php";

use Artemis\Core\Router\Router;
use Artemis\Core\DataBases\DB;

$app = Router::getInstance();
$snippets = DB::new("JSON", "snippets");


session_start();
$app->get("/register", function ($req, $res) {
    $res->render(__DIR__ . "/views/register.php");
    $res->status(200);
});

$app->get("/login", function ($req, $res) {
    $res->render(__DIR__ . "/views/login.php");
    $res->status(200);
});

$app->get("/snippets/new", function ($req, $res) {
    $res->render(__DIR__ . "/views/new.php");
    $res->status(200);
});

require_once "auth.php";



$app->get("/snippets/:id", function ($req, $res) {
    $res->render(__DIR__ . "/views/show.php");
    $res->status(200);
});

$app->get("/api/snippets", function ($req, $res) {
    
    $db = readDatabase();
    $arr = array_map(function($elm) {
        return $elm["children"];
    },$db);

    foreach($arr as &$elm) {
        foreach($elm as &$child) {
             $child["date_ago"] = getTimeAgo($child["date"]);
        
        }
    }
    $res->json($arr);
   
});

function readDatabase()
{
    $data = file_get_contents('database.json');
    return json_decode($data, true);
}

// Function to write data to the JSON file
function writeDatabase($data)
{
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('database.json', $json);
}


$app->get("/", function ($req, $res) {
    $res->render(__DIR__ . "/views/index.php");
    $res->status(200);
});


$app->get("/api/snippets/:id", function ($req, $res) {

    $db = readDatabase();
    function findElementById($data, $id) {
        foreach ($data as $element) {
            if ($element['id'] === $id) {
                return $element;
            }
    
            if (!empty($element['children'])) {
                $result = findElementById($element['children'], $id);
                if ($result) {
                    return $result;
                }
            }
        }
    
        return null;
    }

    $foundElement = findElementById($db, $req->params()["id"]);
    if ($foundElement) {
        $res->jsoN($foundElement);
        $res->status(200);
    } else {
        $res->send($req->params()["id"]);
        $res->status(500);
    }
 
}); 


$app->get("/api/filetrees", function ($req, $res) {
    $res->json(readDatabase());
    $res->status(201);
});

$app->post("/api/filetrees", function ($req, $res) {
    $db = readDatabase();
    array_push($db, ["text" => $req->body()["name"], "children" => [], "id" => uniqid()]);
    writeDatabase($db);
    $res->status(201);
});

$app->post("/api/snippets", function ($req, $res) {
     $data =   [ 
                "date" => date('Y-m-d H:i:s'),
                "user" => $_SESSION["user"],
                "user_id" => (string) $_SESSION["user_id"],
                "text" => $req->body()["title"],
                "code" => $req->body()["code"],
                "language" => $req->body()["language"],
                "folder" => $req->body()["folder"],
     ];
     writeSnippetDatabase($data);
     $res->status(201);
     $res->status(301);
     $res->redirect("/");

    global $snippets;
});


$app->get("/public/:file", function ($req, $res) {
    $path_to_file = explode("/", $req->path())[2];
    header("Content-type:" . $res->getContentType($path_to_file));
    $file = "public/$path_to_file";
    readfile($file);
});

//wildcard route

$app->get("*", function ($req, $res) {
    $res->send("404");
    $res->status(404);
});

$app->listen("/", function () {
});
