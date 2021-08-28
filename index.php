<?php

use Blog\PostMapper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;


require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader('views');
$view = new Environment($loader);

// Database connect
try {
    require_once 'config/database.php';
    $pdo = new PDO(DSN, USERNAME, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    echo 'Connect error:' . $exception->getMessage();
    exit;
}

$postMapper = new PostMapper($pdo);

$app = AppFactory::create();


// Main page route
$app->get('/', function (Request $request, Response $response, $args) use ($view, $postMapper) {
    $posts = $postMapper->getPost();

    $body = $view->render('index.twig', [
        'title' => 'Main page',
        'posts' => $posts
    ]);
    $response->getBody()->write($body);
    return $response;
});

// About page route
$app->get('/about', function(Request $request, Response $response, $args) use ($view) {
    $body = $view->render('about.twig', [
        'title' => 'About page'
    ]);
    $response->getBody()->write($body);
    return $response;
});

// Single post url route
$app->get('/{url_key}', function(Request $request, Response $response, $args) use ($view, $postMapper) {
    $post = $postMapper->getUrlKey((string) $args['url_key']);

    if(empty($post)){
        $body = $view->render('errors/404.twig');
    } else {
        $body = $view->render('post.twig', [
            'post' => $post
        ]);
    }

    $response->getBody()->write($body);
    return $response;
});

$app->run();    