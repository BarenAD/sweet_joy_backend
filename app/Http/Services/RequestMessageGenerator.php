<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 17.08.21
 * Time: 10:33
 */

namespace App\Http\Services;

/**
 * Class RequestMessageGenerator
 * @package App\Http\services
 */
class RequestMessageGenerator
{
    /**
     * @param array $rules
     * @return array
     */
    public function generatedMessages(array $rules)
    {
        $resultMessages = [];
        foreach($rules as $key => $value) {
            if (gettype($value) === 'string') {
                $arrayRules = $this->smartExplodeRule($value);
                foreach($arrayRules as $function) {
                    $dataForFunction = ['key' => $key];
                    $preparedFunction = $function;
                    if (gettype($function) === 'array') {
                        $preparedFunction = $function[0];
                        $dataForFunction['propsFunction'] = $function[1];
                    }
                    $resultMessages[$key.'.'.$preparedFunction] = $this->switchFunction($preparedFunction,$dataForFunction);
                }
            }
        }
        return $resultMessages;
    }

    /**
     * @param string $rule
     * @return array
     */
    private function smartExplodeRule(string $rule)
    {
        $arrayRules = explode('|',$rule);

        return array_map(
            function ($inputRule) {
                $resExplode = explode(':',$inputRule);
                if (count($resExplode) > 1)
                    return $resExplode;
                return $inputRule;
            },
            $arrayRules
        );
    }

    /**
     * @param string $function
     * @param $dataForFunction
     * @return string
     */
    private function switchFunction(string $function, $dataForFunction)
    {
        switch ($function) {
            case 'required': return $this->required($dataForFunction['key']); break;
            case 'numeric': return $this->numeric($dataForFunction['key']); break;
            case 'string': return $this->string($dataForFunction['key']); break;
            case 'max': return $this->max($dataForFunction['key'],$dataForFunction['propsFunction']); break;
            case 'regex': return $this->regex($dataForFunction['key'],$dataForFunction['propsFunction']); break;
        }
        return '';
    }

    /**
     * @param string $key
     * @return string
     */
    private function required(string $key)
    {
        return 'Поле \''.$key.'\' обязательно и не может быть пустым.';
    }

    /**
     * @param string $key
     * @return string
     */
    private function numeric(string $key)
    {
        return 'Поле \''.$key.'\' должно быть целочисленным значением.';
    }

    /**
     * @param string $key
     * @return string
     */
    private function string(string $key)
    {
        return 'Поле \''.$key.'\' должно быть строкой.';
    }

    /**
     * @param string $key
     * @param int $maxLength
     * @return string
     */
    private function max(string $key, int $maxLength)
    {
        $result = 'Поле "'.$key.'". Превышена максимальная длина строки.';
        if($maxLength) {
            $result .= ' Максимальная длина: ' . $maxLength;
        }
        return $result;
    }

    private function regex(string $key, string $mask)
    {
        $result = 'Поле \''.$key.'\' не соответствует маске.';
        if($mask) {
            $result .= ' Маска: \'' . $mask . '\'';
        }
        return $result;
    }
}
