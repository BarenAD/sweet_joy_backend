<?php


namespace Tests\Unit;


use App\Exceptions\BaseException;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BaseExceptionTest extends TestCase
{
    public function testReportWithThrowableAndCustomMessage()
    {
        $anchorThrowable = uniqid('anchor_test_with_throwable_');
        $anchor = uniqid('anchor_test_with_custom_message_');
        try {
            $throwable = new \Exception($anchorThrowable, 1000);
            throw new BaseException('test', $throwable, null, $anchor);
        } catch (BaseException $exception) {
            Log::listen(function ($log) use ($anchor, $anchorThrowable) {
                $context = $log->context;
                $logMessage = $context['data']['base']['message'];
                $this->assertTrue($log->message === $anchor);
                $this->assertTrue(strpos($logMessage, $anchor) !== false);
                $this->assertTrue(strpos($logMessage, $anchorThrowable) !== false);
            });
            $exception->report();
            $render = $exception->render();
            $renderContent = json_decode($render->content());
            $this->assertTrue($render->getStatusCode() === config('exceptions.test.http_code'));
            $this->assertTrue($renderContent->id === config('exceptions.test.id'));
            $this->assertTrue($renderContent->message === $anchor);
        }
    }
}
