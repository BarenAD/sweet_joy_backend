<?php


namespace Tests\Feature;


use App\Exceptions\NoReportException;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;
use Tests\TestApiResource;

class CategoryTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.categories';
        $this->model = new Category();
    }

    public function testIndexWithCache()
    {
        $customParams = $this->seedsBD();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('categories.index').'?withCache=true');
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $customParams);
        $this->assertEquals(Cache::tags(['main_data', 'categories'])->get('cache_categories', null), $customParams);
        $this->mock(
            CategoryRepository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getAll')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('categories.index').'?withCache=true');
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response2->json(), $customParams);
    }
}
