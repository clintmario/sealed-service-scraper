<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Support\Facades\Auth;
use App\Modules\Core\Entities\Core;
use App\Modules\Core\Services\EventService;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        // Added by CMC
        $eventService = Core::getService(EventService::class);
        if ($exception instanceof \Exception) {
            // emails.exception is the template of your email
            // it will have access to the $error that we are passing below
            if (ExceptionHandler::isHttpException($exception)) {
                $content = ExceptionHandler::toIlluminateResponse(ExceptionHandler::renderHttpException($exception), $exception);
            } else {
                $content = ExceptionHandler::toIlluminateResponse(ExceptionHandler::convertExceptionToResponse($exception), $exception);
            }

            $lc2 = (isset($content->original)) ? $content->original : $exception->getMessage();

            // add request info to err msg
            $lc1 = '';
            try {
                $request = request();
                $user = Auth::user();
                $email = !empty($user->email) ? $user->email : '';
                $userId = !empty($user->id) ? $user->id : '';

                $lc1 =
                    '<div class="sf-reset">' .
                    "<h2>-- Request --</h2>" .
                    "<br><b>Current User:</b> " . $email .
                    "<br><b>User Id:</b> " . $userId .
                    "<br><b>Method:</b> " . $request->getMethod() .
                    "<br><b>URI:</b> " . $request->getUri() .
                    "<br><b>IP Address:</b> " . $request->getClientIp() .
                    "<br><b>Referer:</b> " . $request->server('HTTP_REFERER') .
                    "<br><b>Is secure:</b> " . $request->isSecure() .
                    "<br><b>Is ajax:</b> " . $request->ajax() .
                    "<br><b>User agent:</b> " . $request->server('HTTP_USER_AGENT') .
                    "<br><b>Content:</b><br>" . nl2br(htmlentities($request->getContent())) .
                    "</div><br><br>";
            } catch (Exception $e2){}

            if (strpos($lc2, '<div id="sf-resetcontent"') !== false){
                $lc2 = preg_replace('#(<div id="sf-resetcontent.*?)>#i', "$1>$lc1", $lc2);
            }else{
                $lc2 = $lc1 . $lc2;
            }

            $eventObject = new \stdClass();
            $eventObject->content = $lc2;
            $eventObject->exception = new \StdClass();
            $eventObject->exception->file = $exception->getFile();
            $eventObject->exception->line = $exception->getLine();
            //$eventService->fire(EventService::EVENT_GENERATE_EMAIL_ERROR, $eventObject);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
