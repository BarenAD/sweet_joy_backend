<?php

namespace Tests\Feature;

use App\Exceptions\NoReportException;
use App\Models\Document;
use App\Models\DocumentLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Traits\WithoutPermissionsTrait;

class DocumentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;
    use WithoutPermissionsTrait;

    private Document $document;
    private string $currentName;
    private UploadedFile $currentFile;
    private string $pathToDocuments;

    public function setUp(): void {
        parent::setUp();
        $this->document = new Document();
        $this->currentFile = UploadedFile::fake()->create('test.pdf', 100);
        $this->currentName = $this->faker->text(30);
        $this->pathToDocuments = config('filesystems.path_inside_disk.documents');
    }

    public function testAllUsedDocumentsWithCache()
    {
        $documents = $this->document
            ->factory()
            ->count(10)
            ->create()
            ->toArray();
        $documentLocations = DocumentLocation::factory()
            ->count(10)
            ->create();
        $preparedDocuments = [];
        foreach ($documentLocations as $documentLocation) {
            $newPreparedDocument = $documents[rand(0,9)];
            $documentLocation->update(['document_id' => $newPreparedDocument['id']]);
            $newPreparedDocument['url'] = Storage::disk('public')->url($this->pathToDocuments.$newPreparedDocument['urn']);
            $newPreparedDocument['location'] = $documentLocation->identify;
            unset($newPreparedDocument['urn']);
            $preparedDocuments[$documentLocation->identify] = $newPreparedDocument;
        }
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('documents.index.used').'?withCache=true');
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $preparedDocuments);
        $this->mock(
            DocumentLocation::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getAllWithDocuments')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('documents.index.used').'?withCache=true');
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response2->json(), $preparedDocuments);
    }

    public function testIndexDocument()
    {
        $documents = $this->document
            ->factory()
            ->count(10)
            ->create()
            ->toArray();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.documents.index'));
        $response->assertStatus(
            Response::HTTP_OK
        );
        array_walk($documents, function (&$document) {
            $document['url'] = Storage::disk('public')->url($this->pathToDocuments.$document['urn']);
            unset($document['urn']);
        });
        $this->assertEquals($response->json(), $documents);
    }

    public function testShowDocument()
    {
        $document = $this->document
            ->factory()
            ->count(10)
            ->create()
            ->toArray()
            [rand(0, 9)];
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.documents.show', $document['id']));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $document['url'] = Storage::disk('public')->url($this->pathToDocuments.$document['urn']);
        unset($document['urn']);
        $this->assertEquals($response->json(), $document);
    }

    public function testStoreDocument()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.documents.store'),[
                'name' => $this->currentName,
                'document' => $this->currentFile,
            ]);
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertDatabaseHas('documents', [
            'id' => $response['id'],
            'urn' => $response['urn'],
            'name' => $this->currentName,
        ]);
        $this->assertTrue(Storage::disk('public')->exists($this->pathToDocuments.$response['urn']));

        Storage::disk('public')->delete($this->pathToDocuments.$response['urn']);
    }

    public function testStoreInvalidDocument()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.documents.store'), [
                'name' => $this->currentName,
                'document' => UploadedFile::fake()->create('test.pdf', 30000),
            ]);
        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
        $this->assertDatabaseMissing('documents', [
            'name' => $this->currentName,
        ]);
        $response->assertJsonValidationErrors(['document']);
    }

    public function testUpdateDocument()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.documents.store'),[
                'name' => $this->currentName,
                'document' => $this->currentFile,
            ]);

        $newName = $this->faker->text(30);
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->put(route('management.documents.update', $response['id']), [
                'name' => $newName
            ]);
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertDatabaseHas('documents', [
            'id' => $response['id'],
            'urn' => $response['urn'],
            'name' => $newName,
        ]);
        Storage::disk('public')->delete($this->pathToDocuments.$response['urn']);
    }

    public function testDeleteDocument()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.documents.store'),[
                'name' => $this->currentName,
                'document' => $this->currentFile,
            ]);

        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->delete(route('management.documents.destroy', $response['id']));
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertDatabaseMissing('documents', [
            'id' => $response['id'],
            'urn' => $response['urn'],
            'name' => $this->currentName,
        ]);
        $this->assertFalse(Storage::disk('public')->exists($this->pathToDocuments.$response['urn']));
    }
}
