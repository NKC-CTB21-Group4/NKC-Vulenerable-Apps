<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;

use App\Application\Actions\Challenges\User\ListChallengesUsersAction;
use App\Application\Actions\Challenges\User\ViewChallengesUserAction;
use App\Application\Actions\Challenges\User\CreateChallengesUsersAction;
use App\Application\Actions\Challenges\User\DeleteChallengesUsersAction;
use App\Application\Actions\Challenges\User\UpdateChallengesUsersAction;

use App\Application\Actions\Challenges\News\ListChallengesNewsAction;
use App\Application\Actions\Challenges\News\ViewChallengesNewsAction;
use App\Application\Actions\Challenges\News\CreateChallengesNewsAction;
use App\Application\Actions\Challenges\News\DeleteChallengesNewsAction;
use App\Application\Actions\Challenges\News\UpdateChallengesNewsAction;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
    
    // challenges用のAPIエンドポイント
    $app->group('/challenges/api', function (Group $group) {
        $group->get('', function (Request $request, Response $response) {
            // ここにルート処理を書く
            return $response;
        });

        $group->group('/users', function (Group $group) {
            $group->get('', ListChallengesUsersAction::class);
            $group->post('',CreateChallengesUsersAction::class);
            $group->delete('',DeleteChallengesUsersAction::class);
            $group->put('',UpdateChallengesUsersAction::class);
            $group->get('/{id}', ViewChallengesUserAction::class);
        });
        $group->group('/news', function (Group $group) {
            $group->get('',ListChallengesNewsAction::class);
            $group->get('/{id}',ViewChallengesNewsAction::class);
            $group->post('',CreateChallengesNewsAction::class);
            $group->delete('',DeleteChallengesNewsAction::class);
            $group->put('',UpdateChallengesNewsAction::class);
        });
    });
};
