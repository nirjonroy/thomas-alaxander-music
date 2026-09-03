<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;
use App\Support\PublishingApiResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (AccessDeniedHttpException $exception, $request) {
            if ($this->isPublishingApiRequest($request)) {
                return PublishingApiResponse::error('Authenticated token does not have the required ability.', [], 403);
            }
        });

        $this->renderable(function (TooManyRequestsHttpException $exception, $request) {
            if ($this->isPublishingApiRequest($request)) {
                return PublishingApiResponse::error('Too many publishing API requests.', [], 429);
            }
        });

        $this->renderable(function (ValidationException $exception, $request) {
            if ($this->isPublishingApiRequest($request)) {
                return PublishingApiResponse::error('The given data was invalid.', $exception->errors(), 422);
            }
        });

        $this->renderable(function (NotFoundHttpException $exception, $request) {
            if ($this->isPublishingApiRequest($request)) {
                return PublishingApiResponse::error('Publishing resource was not found.', [], 404);
            }
        });

        $this->renderable(function (Throwable $exception, $request) {
            if (! $this->isPublishingApiRequest($request)) {
                return null;
            }

            if ($exception instanceof AuthenticationException || $exception instanceof HttpExceptionInterface) {
                return null;
            }

            Log::error('Publishing API unexpected error.', [
                'request_id' => $request->attributes->get('publishing_request_id'),
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
            ]);

            return PublishingApiResponse::error('An unexpected publishing API error occurred.', [], 500);
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isPublishingApiRequest($request)) {
            return PublishingApiResponse::error('Unauthenticated.', [], 401);
        }

        if($request->expectsJson()){
            return response()->json(['message' => 'UnAuthenticated'], 401);
        }
        $guard=Arr::get($exception->guards(),'0');
        switch($guard){
            case 'admin':
                $login="/admin/login";
            break;

            default:
                $login="/admin/login";
        }

        return Redirect()->guest($login);
    }

    private function isPublishingApiRequest($request): bool
    {
        return $request->is('api/v1/publishing') || $request->is('api/v1/publishing/*');
    }
}
