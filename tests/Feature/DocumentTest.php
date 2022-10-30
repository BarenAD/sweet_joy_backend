<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\WithoutPermissionsTrait;

class DocumentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;
    use WithoutPermissionsTrait;

    private Model $document;
    private string $currentName;
    private UploadedFile $currentFile;
    private string $pathToDocuments;

    public function setUp(): void {
        parent::setUp();
        $this->currentFile = UploadedFile::fake()->create('test.pdf', 100);
        $this->currentName = $this->faker->text(30);
        $this->pathToDocuments = config('filesystems.path_inside_disk.documents');
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
