<?php


namespace Tests\Unit;


use App\Exceptions\BaseException;
use App\Exceptions\NoReportException;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class NoReportExceptionTest extends TestCase
{
    public function testWithCustomMessage()
    {
        $anchorThrowable = uniqid('anchor_test_with_throwable_');
        $anchor = uniqid('anchor_test_with_custom_message_');
        try {
            $throwable = new \Exception($anchorThrowable, 1000);
            throw new NoReportException('test', $throwable, null, $anchor);
        } catch (BaseException $exception) {
            Log::listen(function ($log) {
                $this->markTestIncomplete();
            });
            $exception->report();
            $render = $exception->render();
            $renderContent = json_decode($render->content());
            $this->assertTrue($render->getStatusCode() === 100);
            $this->assertTrue($renderContent->id === -1);
            $this->assertTrue($renderContent->message === $anchor);
        }
    }
}
