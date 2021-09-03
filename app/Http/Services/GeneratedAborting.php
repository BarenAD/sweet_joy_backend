<?php


namespace App\Http\Services;


use Illuminate\Http\Exceptions\HttpResponseException;

class GeneratedAborting
{
    public static function notFound()
    {
        GeneratedAborting::_generatedHttpException([
            'message' => 'Ничего не найдено.',
            'code' => 404,
        ]);
    }

    public static function accessDeniedGrandsAdmin()
    {
        GeneratedAborting::_generatedHttpException([
            'message' => 'Недостаточно прав администрирования.',
            'code' => 403,
        ]);
    }

    public static function adminAlreadyExist()
    {
        GeneratedAborting::_generatedHttpException([
            'message' => 'Данный администратор уже существует. Используйте PUT метод.',
            'code' => 409,
        ]);
    }

    public static function youAreNotAdmin()
    {
        GeneratedAborting::_generatedHttpException([
            'message' => 'Вы не являетесь администратором.',
            'code' => 403,
        ]);
    }

    public static function internalServerErrorCustomMessage(string $string)
    {
        GeneratedAborting::_generatedHttpException([
            'message' => $string,
            'code' => 500,
        ]);
    }

    public static function internalServerError(\Exception $exception)
    {
        GeneratedAborting::_generatedHttpException([
            'message' => $exception->getMessage() . '  CODE::' . $exception->getCode(),
            'code' => 500,
        ]);
    }

    public static function _generatedHttpException($paramsObject)
    {
        throw new HttpResponseException(
            response()->json([
                    'message' => $paramsObject->message || 'Внутренняя ошибка сервера.',
                    'errors' => $paramsObject->errors || [],
                ]
                ,
                $paramsObject->code || 500
            )
        );
    }
}
