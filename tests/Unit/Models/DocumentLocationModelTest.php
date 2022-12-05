<?php


namespace Tests\Unit\Models;

use App\Models\Document;
use App\Models\DocumentLocation;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentLocationModelTest extends TestCase
{
    use RefreshDatabase;

    private DocumentLocation $documentLocation;
    private Model $document;

    public function setUp(): void
    {
        parent::setUp();
        $this->documentLocation = app()->make(DocumentLocation::class);
        $this->document = Document::factory()->create();
        DocumentLocation::factory([
            'document_id' => $this->document->id
        ])
            ->count(5)
            ->create();
    }

    public function testScopeSchedulesWith()
    {
        $result = $this->documentLocation->withDocuments(true)->get();
        $toArrayDocument = $this->document->toArray();
        foreach ($result->toArray() as $value) {
            $this->assertTrue(isset($value['document']));
            $this->assertEquals($value['document'], $toArrayDocument);
        }
    }

    public function testScopeSchedulesWithNot()
    {
        $result = $this->documentLocation->withDocuments(false)->get();
        foreach ($result->toArray() as $value) {
            $this->assertFalse(isset($value['document']));
        }
    }
}
