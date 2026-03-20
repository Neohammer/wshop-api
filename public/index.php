<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Application\Service\AuthService;
use App\Application\Service\CreateStoreService;
use App\Application\Service\DeleteStoreService;
use App\Application\Service\GetStoreService;
use App\Application\Service\ListStoresService;
use App\Application\Service\UpdateStoreService;
use App\Application\Validator\StoreValidator;
use App\Infrastructure\Http\Controller\AuthController;
use App\Infrastructure\Http\Controller\StoreController;
use App\Infrastructure\Http\JsonResponse;
use App\Infrastructure\Http\Middleware\JwtAuthMiddleware;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Router;
use App\Infrastructure\Logging\FileLogger;
use App\Infrastructure\Persistence\DatabaseConnection;
use App\Infrastructure\Persistence\PdoStoreRepository;
use App\Infrastructure\Persistence\PdoUserRepository;
use App\Infrastructure\Security\JwtService;
use App\Shared\Exception\HttpException;
use App\Shared\Exception\ValidationException;

$logger = new FileLogger(__DIR__ . '/../storage/logs/app.log');


try {
    $request = Request::fromGlobals();
} catch (HttpException $e) {
    $logger->error('Request parsing error', [
        'message' => $e->getMessage(),
        'status' => $e->getStatusCode(),
    ]);

    JsonResponse::error($e->getMessage(), $e->getStatusCode())->send();
    exit;
} catch (\Throwable $e) {
    $logger->error('Unhandled request parsing exception', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);

    JsonResponse::error('Internal Server Error', 500)->send();
    exit;
}


$router = new Router();

$pdo = DatabaseConnection::create(__DIR__ . '/../database/database.sqlite');
$storeRepository = new PdoStoreRepository($pdo);
$storeValidator = new StoreValidator();

$jwtService = new JwtService('my-super-secret-key', 3600);
$userRepository = new PdoUserRepository($pdo);
$authService = new AuthService($userRepository, $jwtService);
$authController = new AuthController($authService);
$jwtAuthMiddleware = new JwtAuthMiddleware($jwtService);

$storeController = new StoreController(
    new ListStoresService($storeRepository),
    new GetStoreService($storeRepository),
    new CreateStoreService($storeRepository, $storeValidator),
    new UpdateStoreService($storeRepository, $storeValidator),
    new DeleteStoreService($storeRepository)
);

$router->get('/health', function () {
    return JsonResponse::success(['status' => 'ok']);
});


$router->post('/login', function (Request $request) use ($authController) {
    return $authController->login($request);
});


$router->get('/stores', function (Request $request) use ($storeController, $jwtAuthMiddleware) {
    $jwtAuthMiddleware->handle($request);
    return $storeController->list($request);
});

$router->get('/stores/{id}', function (Request $request, string $id) use ($storeController, $jwtAuthMiddleware) {
    $jwtAuthMiddleware->handle($request);
    return $storeController->get((int)$id);
});

$router->post('/stores', function (Request $request) use ($storeController, $jwtAuthMiddleware) {
    $jwtAuthMiddleware->handle($request);
    return $storeController->create($request);
});

$router->put('/stores/{id}', function (Request $request, string $id) use ($storeController, $jwtAuthMiddleware) {
    $jwtAuthMiddleware->handle($request);
    return $storeController->update($request, (int)$id);
});

$router->delete('/stores/{id}', function (Request $request, string $id) use ($storeController, $jwtAuthMiddleware) {
    $jwtAuthMiddleware->handle($request);
    return $storeController->delete($request, (int)$id);
});


try {
    $response = $router->dispatch($request);
} catch (ValidationException $e) {
    $logger->error('Validation error', [
        'message' => $e->getMessage(),
        'errors' => $e->getErrors(),
        'path' => $request->getPath(),
        'method' => $request->getMethod(),
    ]);

    $response = JsonResponse::error($e->getMessage(), $e->getStatusCode(), $e->getErrors());
} catch (HttpException $e) {
    $logger->error('HTTP exception', [
        'message' => $e->getMessage(),
        'status' => $e->getStatusCode(),
        'path' => $request->getPath(),
        'method' => $request->getMethod(),
    ]);

    $response = JsonResponse::error($e->getMessage(), $e->getStatusCode());
} catch (Throwable $e) {
    $logger->error('Unhandled exception', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'path' => $request->getPath(),
        'method' => $request->getMethod(),
    ]);

    $response = JsonResponse::error('Internal Server Error', 500);
}

$response->send();