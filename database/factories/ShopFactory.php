<?php

namespace Database\Factories;

use App\Models\Shop;

class ShopFactory extends CoreFactory
{
    protected $model = Shop::class;

    protected array $availableMapIntegration = [
        '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d916.9619145338502!2d37.60946573413041!3d55.76161982703659!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54a443a83ac07%3A0xadf75d017913401c!2z0J_QsNC80Y_RgtC90LjQuiDQrtGA0LjRjiDQlNC-0LvQs9C-0YDRg9C60L7QvNGD!5e0!3m2!1sru!2sru!4v1630992435798!5m2!1sru!2sru" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
        '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1587.9195119691037!2d37.641745010155795!3d55.747089465302615!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54af0623b063f%3A0xa526e27d4e68d917!2z0JLRi9GB0L7RgtC60LAg0L3QsCDQmtC-0YLQtdC70YzQvdC40YfQtdGB0LrQvtC5INC90LDQsdC10YDQtdC20L3QvtC5!5e0!3m2!1sru!2sru!4v1630992565070!5m2!1sru!2sru" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
        '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3664.8822000959376!2d37.67746707203464!3d55.79313763771376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b535b7788c0391%3A0x1dbb7567c7495d08!2z0J_QsNGA0Log0KHQvtC60L7Qu9GM0L3QuNC60Lg!5e0!3m2!1sru!2sru!4v1630992589647!5m2!1sru!2sru" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
    ];

    public function definition()
    {
        return $this->decorateTimestamp([
            'address' => $this->faker->address(),
            'phone' => $this->faker->regexify('/^[7]\d{10}$/'),
            'map_integration' => $this->availableMapIntegration[array_rand($this->availableMapIntegration, 1)],
            'schedule_id' => null,
        ]);
    }
}
