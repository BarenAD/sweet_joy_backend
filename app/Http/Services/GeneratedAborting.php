<?php


namespace App\Http\Services;


use Illuminate\Http\Exceptions\HttpResponseException;

class GeneratedAborting
{
    public static function notFound() {
        GeneratedAborting::_generatedHttpException([
            'Ничего не найдено'
        ], 404);
    }

    public static function accessDeniedGrandsAdmin() {
        GeneratedAborting::_generatedHttpException([
            'Недостаточно прав администрирования.'
        ], 403);
    }

    public static function adminAlreadyExist() {
        GeneratedAborting::_generatedHttpException([
            'Данный администратор уже существует. Используйте PUT метод.'
        ], 409);
    }

    public static function youAreNotAdmin() {
        GeneratedAborting::_generatedHttpException([
            'Вы не являетесь администратором.'
        ], 403);
    }

    public static function internalServerErrorCustomMessage(string $string)
    {
        GeneratedAborting::_generatedHttpException([
            $string
        ], 500);
    }

    public static function internalServerError(\Exception $exception)
    {
        GeneratedAborting::_generatedHttpException([
            $exception->getMessage() . '  CODE::' . $exception->getCode()
        ], 500);
    }

    public static function _generatedHttpException($errors = [], $code)
    {
        throw new HttpResponseException(
            response()->json([
                    'errors' => $errors
                ]
                ,
                $code
            )
        );
    }
}
