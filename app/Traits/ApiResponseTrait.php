<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    private $defaultSuccessResponseData = [ 'message' => 'SUCCESS' ];

    public function setDefaultSuccessResponse( ?array $content = NULL ) : self
    {
        $this->defaultSuccessResponseData = $content ?? [];

        return $this;
    }

    /**
     * @param array|Arrayable|JsonSerializable|null $contents
     */
    public function respondWithSuccess( $contents = NULL ) : JsonResponse
    {
        $contents = $this->morphToArray( $contents ) ?? [];

        $data = [] === $contents
            ? $this->defaultSuccessResponseData
            : $contents;

        return $this->apiResponse( data: $data );
    }

    public function respondOk( string $message ) : JsonResponse
    {
        return $this->respondWithSuccess( [ 'message' => $message ] );
    }

    public function respondOkUpdated( ?string $message = NULL, ?string $code = 'MODEL_UPDATED' ) : JsonResponse
    {
        return $this->respondWithSuccess(
            [ 'message' => $message ?? __( 'locale.api.alert.updated_successfully' ), 'code' => $code ],
        );
    }

    /**
     * @param string|\Exception $message
     * @param string|null $key
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound( ?string $message = NULL, ?string $code = 'MODEL_NOT_FOUND' ) : JsonResponse
    {
        return $this->apiResponse(
            data: [ 'message' => isset( $message )
                ? $this->morphMessage( $message )
                : __( 'locale.api.errors.not_found_record_message' ),
                'code'    => $code,
            ],
            statusCode: Response::HTTP_NOT_FOUND,
        );
    }

    public function respondUnAuthenticated( ?string $message = NULL, ?string $code = 'AUTHENTICATION_ERROR' ) : JsonResponse
    {
        return $this->apiResponse(
            data: [ 'message' => $message ?? __( 'locale.api.errors.user_unauthenticated' ), 'code' => $code ],
            statusCode: Response::HTTP_UNAUTHORIZED,
        );
    }

    public function respondForbidden( ?string $message = NULL, ?string $code = 'FORBIDDEN_ERROR' ) : JsonResponse
    {
        return $this->apiResponse(
            data: [ 'message' => $message ?? __( 'locale.api.errors.user_does_not_have_necessary_permissions' ), 'code' => $code ],
            statusCode: Response::HTTP_FORBIDDEN,
        );
    }

    public function respondError( ?string $message = NULL, ?string $code = 'RESPONSE_ERROR' ) : JsonResponse
    {
        return $this->apiResponse(
            data: [ 'message' => $message ?? __( 'locale.api.errors.response_message' ), 'code' => $code ],
            statusCode: Response::HTTP_BAD_REQUEST,
        );
    }

    /**
     * @param array|Arrayable|JsonSerializable|null $data
     */
    public function respondCreated( $data = NULL ) : JsonResponse
    {
        $data ?? $data = [];

        return $this->apiResponse(
            data: $this->morphToArray( $data ),
            statusCode: Response::HTTP_CREATED
        );
    }

    /**
     * @param $message
     * @param string|null $key
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondFailedValidation( $message, ?string $code = 'VALIDATION_ERROR' ) : JsonResponse
    {
        return $this->apiResponse(
            data: [ 'message' => $message, 'code' => $code ],
            statusCode: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function respondTeapot() : JsonResponse
    {
        return $this->apiResponse(
            data: [ 'message' => 'I\'m a teapot' ],
            statusCode: Response::HTTP_I_AM_A_TEAPOT
        );
    }

    /**
     * @param array|Arrayable|JsonSerializable|null $data
     */
    public function respondNoContent( $data = NULL, ?string $code = 'NO_CONTENT' ) : JsonResponse
    {
        if ( $data ) {
            $data = $this->morphToArray( $data );
        }

        return $this->apiResponse(
            data: [ 'message' => $data, 'code' => $code ],
            statusCode: Response::HTTP_NO_CONTENT,
        );
    }

    private function apiResponse( array $data, int $statusCode = Response::HTTP_OK ) : JsonResponse
    {
        return response()->json( $data, $statusCode );
    }

    /**
     * @param array|Arrayable|JsonSerializable|null $data
     * @return array|null
     */
    private function morphToArray( $data )
    {
        if ( $data instanceof Arrayable ) {
            return $data->toArray();
        }

        if ( $data instanceof JsonSerializable ) {
            return $data->jsonSerialize();
        }

        return $data;
    }

    /**
     * @param string|\Exception $message
     * @return string
     */
    private function morphMessage( $message ) : string
    {
        return $message instanceof \Exception
            ? $message->getMessage()
            : $message;
    }
}
