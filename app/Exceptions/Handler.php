<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (UnauthorizedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка аутентификации',
            ], 401)->header('Charset', 'utf-8')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        });

        $this->renderable(function (RespondWithMessageException $e) {
            return $e->response;
        });

        $this->renderable(function (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Некорректный токен'
            ])->header('Charset', 'utf-8')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        });

        $this->renderable(function (AccessDeniedHttpException $e) {
            $previous = $e->getPrevious();

            if ($previous instanceof AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Действие не авторизовано',
                ], 403)->header('Charset', 'utf-8')
                    ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            }

            return null;
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ресурс не найден'
            ], 404)->header('Charset', 'utf-8')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Неверный http-метод, используйте ' . $e->getHeaders('Allow')['Allow']
            ], 405)->header('Charset', 'utf-8')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        });

        $this->renderable(function (BadCredentials $e) {
            return response()->json([
                'success' => false,
                'message' => 'Неверный логин или пароль'
            ], 401)->header('Charset', 'utf-8')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        });

        $this->renderable(function (BadApiVersion $e) {
            return $e->throw();
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
