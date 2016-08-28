<?php
use App\Http\Controllers\AuthenticationController;
$app->group(['prefix' => 'api'], function () use ($app) {
    $app->post('authenticate', [
        'uses' => AuthenticationController::class . '@authenticate',
        'as' => 'sign_in'
    ]);
});
$app->group(['prefix' => 'api', 'middleware' => ['before' => 'jwt-auth']], function () use ($app) {
    $app->get('/todo', function () use ($app) {
        $user = $app['tymon.jwt.auth']->toUser();
        return ['todos' => [
            'items' => ['Code awesome stuff', 'Feed the cat'],
            'owner' => $user->id,
            'name' => $user->name,
        ]];
    });
});
$app->get('/', function () {
    $url = route('sign_in');
    return <<<HTML
<form method="post" action="$url">
    <input type="email" name="email">
    <input type="text" name="password">
    <input type="submit" value="Submit">
</form>
HTML;
});