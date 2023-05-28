<?php
require_once __DIR__ . "/vendor/autoload.php";


use Artemis\Core\Router\Router;
use Artemis\Core\DataBases\DB;

$app = Router::getInstance();
$snippets = DB::new("JSON", "snippets");

$app->get("/snippets/:id", function ($req, $res) {
    $res->render(__DIR__ . "/views/show.php");
    $res->status(200);
});
$app->get("/api/snippets", function ($req, $res) {
    global $snippets;
    $res->json($snippets->find([]));
    $res->status(201);
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

    $db = readDatabase();
    $key = array_search($req->body()["id"], array_column($db, 'id'));

    if ($key !== false) {
        array_push(
            $db[$key]["children"],
            [
                "text" => $req->body()["title"],
                "id" => uniqid(),
                "code" => $req->body()["code"],
                "language" => $req->body()["language"]
            ]
        );
        writeDatabase($db);

        $res->status(201);
    } else {
        $res->status(500);
    }
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
