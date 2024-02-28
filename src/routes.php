<?php

return [
    '~^hello/(.*)$~' => [\MyProject\Controllers\MainController::class, 'sayHello'],
    '~^bye/(.*)$~' => [\MyProject\Controllers\MainController::class, 'sayBye'],
    '~^articles/(\d+)$~' => [\MyProject\Controllers\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'edit'],
    '~^$~' => [\MyProject\Controllers\MainController::class, 'main'],
    '~^articles/add$~' => [\MyProject\Controllers\ArticlesController::class, 'add'],
    '#^articles/(\d+)/delete$#' => [\MyProject\Controllers\ArticlesController::class, 'delete'],
    '~^users/register$~' => [\MyProject\Controllers\UsersController::class, 'signUp'],
    '~^users/login$~' => [\MyProject\Controllers\UsersController::class, 'login'],
    '~^users/(\d+)/activate/(.+)$~' => [\MyProject\Controllers\UsersController::class, 'activate'],
    '~^logout$~' => [\MyProject\Controllers\UsersController::class, 'logout'],
    '~^(\d+)$~' => [\MyProject\Controllers\MainController::class, 'page'],
    '~^before/(\d+)$~' => [\MyProject\Controllers\MainController::class, 'before'],
    '~^after/(\d+)$~' => [\MyProject\Controllers\MainController::class, 'after'],

];

?>