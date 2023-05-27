<?php
require_once __DIR__ . "/vendor/autoload.php";


use Artemis\Core\Router\Router;
use Artemis\Core\DataBases\DB;
$app = Router::getInstance();
$snippets = DB::new("JSON","snippets");

$app->get("/", function ($req, $res) {
    $res->render(__DIR__ . "/views/index.php");
    $res->status(200);
});


$app->get("/api/snippets", function ($req, $res) {
    global $snippets;
    $res->json($snippets->find([]));
    $res->status(201);
});

$app->post("/api/snippets", function ($req, $res) {
    global $snippets;
    $snippets->create($req->body());
    $res->status(201);
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
