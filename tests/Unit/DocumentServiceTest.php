<?php


namespace Tests\Unit;


use App\Exceptions\NoReportException;
use App\Http\Services\DocumentService;
use App\Repositories\DocumentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class DocumentServiceTest extends TestCase
{
    use RefreshDatabase;

    private string $pathToDocuments;

    public function setUp(): void
    {
        parent::setUp();
        $this->pathToDocuments = config('filesystems.path_inside_disk.documents');
    }

    public function testStore()
    {
        $documentService = app()->make(DocumentService::class);
        $uploadFile = UploadedFile::fake()->create('test.pdf', 10);
        $documentModel = $documentService->store('test', $uploadFile);
        $this->assertModelExists($documentModel);
        $this->assertTrue(
            Storage::disk('public')
                ->exists($this->pathToDocuments . $documentModel->urn)
        );
        Storage::disk('public')->delete($this->pathToDocuments.$documentModel->urn);
    }

    public function testStoreException()
    {
        $this->mock(
            DocumentRepository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('store')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $documentService = app()->make(DocumentService::class);
        $uploadFile = UploadedFile::fake()->create('test.pdf', 10);
        $countFilesBeforeTryStore = count(Storage::disk('public')->allFiles($this->pathToDocuments));
        try {
            $documentService->store('test', $uploadFile);
            $this->markTestIncomplete();
        } catch (\Throwable $exception) {
            $this->assertTrue($exception->getMessage() === config('exceptions.file_is_not_stored.message'));
            $countFilesAfterTryStore = count(Storage::disk('public')->allFiles($this->pathToDocuments));
            $this->assertTrue($countFilesBeforeTryStore === $countFilesAfterTryStore);
        }
    }

    public function testDestroy()
    {
        $documentService = app()->make(DocumentService::class);
        $uploadFile = UploadedFile::fake()->create('test.pdf', 10);
        $documentModel = $documentService->store('test', $uploadFile);
        $documentService->destroy($documentModel->id);
        $this->assertModelMissing($documentModel);
        $this->assertFalse(
            Storage::disk('public')
                ->exists($this->pathToDocuments . $documentModel->urn)
        );
    }

    public function testDestroyException()
    {
        $documentService = app()->make(DocumentService::class);
        $uploadFile = UploadedFile::fake()->create('test.pdf', 10);
        $documentModel = $documentService->store('test', $uploadFile);
        $this->mock(
            DocumentRepository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('destroy')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $documentService = app()->make(DocumentService::class);
        try {
            $documentService->destroy($documentModel->id);
            $this->markTestIncomplete();
        } catch (\Throwable $exception) {
            $this->assertTrue($exception->getMessage() === config('exceptions.file_is_not_destroy.message'));
            $this->assertModelExists($documentModel);
            $this->assertTrue(
                Storage::disk('public')
                    ->exists($this->pathToDocuments . $documentModel->urn)
            );
        }
        Storage::disk('public')->delete($this->pathToDocuments.$documentModel->urn);
    }
}
