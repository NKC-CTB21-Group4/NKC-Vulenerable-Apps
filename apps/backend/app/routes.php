<?php

declare(strict_types=1);

use App\Application\Actions\Main\User\ViewUserAction;
use App\Application\Actions\Main\User\CreateUserAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\Main\Auth\GenerateTokenAction;
use App\Application\Actions\Main\Auth\RevokeTokenAction;


use App\Application\Actions\Main\Post\ViewPostAction;
use App\Application\Actions\Main\Post\ListUserPostsAction;
use App\Application\Actions\Main\Post\CreatePostAction;
use App\Application\Actions\Main\Post\DeletePostAction;
use App\Application\Actions\Main\Post\ListRecommendPostsAction;
use App\Application\Middleware\Challenges\ChallengesJwtMiddleware;
use App\Application\Middleware\Main\JwtMiddleware;
use App\Application\Middleware\Main\AdminJwtMiddleware;
use App\Application\Actions\Main\Reaction\HandleFavoriteAction;
use App\Application\Actions\Main\Reaction\GetFavsCountByPost;

use App\Application\Actions\Main\Admin\User\CreateAdminUserAction;
use App\Application\Actions\Main\Admin\User\ListAdminUserAction;
use App\Application\Actions\Main\Admin\User\ViewAdminUserAction;

use App\Application\Actions\Challenges\Auth\ChallengesGenerateTokenAction;
use App\Application\Actions\Challenges\Auth\ChallengesRevokeTokenAction;

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

use App\Application\Actions\Challenges\Diary\ListChallengesDiaryAction;
use App\Application\Actions\Challenges\Diary\ListPublicChallengesDiaryAction;
use App\Application\Actions\Challenges\Diary\ListMyChallengesDiaryAction;
use App\Application\Actions\Challenges\Diary\ViewChallengesDiaryAction;
use App\Application\Actions\Challenges\Diary\ViewPublicChallengesDiaryAction;
use App\Application\Actions\Challenges\Diary\ViewMyChallengesDiaryAction;

use App\Application\Actions\Challenges\Diary\CreateChallengesDiaryAction;
use App\Application\Actions\Challenges\Diary\DeleteChallengesDiaryAction;
use App\Application\Actions\Challenges\Diary\UpdateChallengesDiaryAction;

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
    
    $app->get('/posts',ListRecommendPostsAction::class);

    $app->group('/favorite/posts',function(Group $group){
        $group->post('/{postId}',HandleFavoriteAction::class)->add(JwtMiddleware::class);
        $group->get('/{postId}',GetFavsCountByPost::class);
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->post('', CreateUserAction::class);
        $group->get('/{id}', ViewUserAction::class);
        $group->group('/{userId}/posts', function (Group $group) {
            $group->get('',ListUserPostsAction::class);
            $group->post('',CreatePostAction::class)->add(JwtMiddleware::class);
            $group->get('/{postId}', ViewPostAction::class);
            $group->delete('/{postId}',DeletePostAction::class)->add(JwtMiddleware::class);
        });
    });

    $app->group('/admin',function(Group $group){
        $group->group('/users',function(Group $group){
            $group->get('',ListAdminUserAction::class)->add(AdminJwtMiddleware::class);
            $group->get('/{userId}',ViewAdminUserAction::class)->add(AdminJwtMiddleware::class);
            $group->post('',CreateAdminUserAction::class)->add(AdminJwtMiddleware::class);
        });
    });

    $app->group('/auth',function (Group $group){
        $group->post('/token',GenerateTokenAction::class);
        $group->get('/test',function (Request $request,Response $response){
            $response->getBody()->write('Authentication successful');
            return $response;
        })->add(JwtMiddleware::class);
        $group->post('/revoke-token',RevokeTokenAction::class);
    });
    
    // challenges用のAPIエンドポイント
    $app->group('/challenges/api', function (Group $group) {
        $group->get('', function (Request $request, Response $response) {
            // ここにルート処理を書く
            return $response;
        });


        $group->group('/auth',function (Group $group){
            $group->post('/token',ChallengesGenerateTokenAction::class);
            $group->get('/test',function (Request $request, Response $response) {
                $response->getBody()->write('Authentication successful');
                return $response;
            })->add(ChallengesJwtMiddleware::class);
            $group->post('/revoke-token',ChallengesRevokeTokenAction::class);
        });

        $group->group('/users', function (Group $group) {
            // adminのみ
            $group->get('', ListChallengesUsersAction::class)->add(ChallengesJwtMiddleware::class);

            // 認証必須 adminは自由
            $group->delete('',DeleteChallengesUsersAction::class)->add(ChallengesJwtMiddleware::class);
            $group->put('',UpdateChallengesUsersAction::class)->add(ChallengesJwtMiddleware::class);
            $group->get('/{id}', ViewChallengesUserAction::class)->add(ChallengesJwtMiddleware::class);

            // public
            $group->post('',CreateChallengesUsersAction::class);        
        });

        $group->group('/news', function (Group $group) {
            // admin
            $group->post('',CreateChallengesNewsAction::class)->add(ChallengesJwtMiddleware::class);
            $group->delete('',DeleteChallengesNewsAction::class)->add(ChallengesJwtMiddleware::class);
            $group->put('',UpdateChallengesNewsAction::class)->add(ChallengesJwtMiddleware::class);
            $group->get('',ListChallengesNewsAction::class)->add(ChallengesJwtMiddleware::class);
            $group->get('/{id}',ViewChallengesNewsAction::class)->add(ChallengesJwtMiddleware::class);

            //public は公開されているもののみ

        });

        $group->group('/diary', function (Group $group) {
            //auth
            $group->put('', UpdateChallengesDiaryAction::class)->add(ChallengesJwtMiddleware::class);
            $group->post('', CreateChallengesDiaryAction::class)->add(ChallengesJwtMiddleware::class);
            $group->delete('', DeleteChallengesDiaryAction::class)->add(ChallengesJwtMiddleware::class);
            $group->get('/my-diary',ListMyChallengesDiaryAction::class)->add(ChallengesJwtMiddleware::class);
            $group->get('/my-diary/{id}',ViewMyChallengesDiaryAction::class)->add(ChallengesJwtMiddleware::class);

            //publicは、公開されているもののみ /privateは、認証されている場合
            $group->get('/{id}', ViewPublicChallengesDiaryAction::class);
            $group->get('', ListPublicChallengesDiaryAction::class);

        });
            
    });
};
