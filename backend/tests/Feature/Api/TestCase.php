<?php

namespace Tests\Feature\Api;

use App\User;
use Tests\CreatesApplication;
use Tests\TestCase as BaseTestCase;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;

class TestCase extends BaseTestCase
{

    use CreatesApplication;

    /**
     * @var User
     */
    protected $user;
    /**
     * Set the currently logged in user for the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string|null                                $driver
     * @return $this
     */
    public function actingAs(UserContract $user, $driver = null)
    {
        $this->user = $user;
        return $this;
    }
    /**
     * Call the given URI and return the Response.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array  $parameters
     * @param  array  $cookies
     * @param  array  $files
     * @param  array  $server
     * @param  string $content
     * @return \Illuminate\Http\Response
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        if ($this->requestNeedsToken($method, $uri)) {
            $server = $this->attachToken($server);
        }
        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }
    /**
     * @return $this
     */
    protected function actingAsAdmin()
    {
        $user = new User();
        $user->email = 'ojolivos@gmail.com';
        $user->password = bcrypt('secret');
        return $this->actingAs($user);
    }


    /**
     * @param string $method
     * @param string $uri
     * @return bool
     */
    protected function requestNeedsToken($method, $uri)
    {
        return !('/auth/login' === $uri && 'POST' === $method);
    }
    /**
     * @param array $server
     * @return string
     */
    protected function attachToken(array $server)
    {
        return array_merge($server, $this->transformHeadersToServerVars([
            'Authorization' => 'bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTU2NjU3MzQ0MiwiZXhwIjoxNTY2NTkxNDQyLCJuYmYiOjE1NjY1NzM0NDIsImp0aSI6Im93bkk0azM5WkFBVWdSM0wiLCJzdWIiOjEsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.8DS67FwTyrB-w2fbR7XtPeQ0h-U-o0nuE7S2l2YlGyg',
            'Accept' => 'application/json'
        ]));
    }

    /**
     * @return string
     */
    protected function getJwtToken()
    {

        return JWTAuth::fromUser($this->user);
    }

    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }



//    protected function setUp()
//    {
//        /**
//         * This disables the exception handling to display the stacktrace on the console
//         * the same way as it shown on the browser
//         */
//        parent::setUp();
//        $this->disableExceptionHandling();
//    }
//
//    protected function disableExceptionHandling()
//    {
//        $this->app->instance(ExceptionHandler::class, new class extends Handler {
//            public function __construct() {}
//
//            public function report(\Exception $e)
//            {
//                // no-op
//            }
//
//            public function render($request, \Exception $e) {
//                throw $e;
//            }
//        });
//    }
}
