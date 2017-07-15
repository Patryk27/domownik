<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler
	extends ExceptionHandler {

	/**
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
	 * @param \Exception $exception
	 * @return void
	 */
	public function report(Exception $exception) {
		parent::report($exception);
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \Exception $exception
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $exception) {
		$exceptionClass = get_class($exception);
		$canReport = !in_array($exceptionClass, $this->dontReport, true);

		if (config('app.debug') && $canReport) {
			return $this->renderExceptionWithWhoops($exception);
		}

		return parent::render($request, $exception);
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \Illuminate\Auth\AuthenticationException $exception
	 * @return \Illuminate\Http\Response
	 */
	protected function unauthenticated($request) {
		if ($request->expectsJson()) {
			return response()->json(['error' => 'Unauthenticated.'], 401);
		}

		// @todo: show error 'you must be logged in to continue'

		return redirect()->route('dashboard.user.login');
	}

	/**
	 * @param \Exception $exception
	 * @return \Illuminate\Http\Response
	 */
	protected function renderExceptionWithWhoops(Exception $exception) {
		$whoops = new \Whoops\Run();
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

		return new \Illuminate\Http\Response(
			$whoops->handleException($exception)
		);
	}

}
