<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    protected $TAGS=['', 'Limited Stock', 'Deal of the day'];
    protected $COMPANY=['Fresh2Arrive', 'Manali Delights', 'Fresh Farm'];
    protected $IMAGES=[
        'products/3/414_HALDIRAM_CHIPS2.JPG',
'products/5/656_BICANO_NAMKEEN2.JPG',
'products/539/911_BICANO_CHIPS2.JPG',
'sizeimage/26/866_BICANO_NAMKEEN.JPG',
    ];
    protected $PACK=['500-600gm', '1kg', '800-1000gm'];

    protected $UNIT=['Kg', 'Litre'];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company'=>$this->COMPANY[rand(0,2)],
            'name'=>$this->faker->name,
            'image'=>$this->IMAGES[rand(0,3)],
            'display_pack_size'=>$this->PACK[rand(0,2)],
            'price_per_unit'=>rand(10,20),
            'cut_price_per_unit'=>rand(25, 50),
            'unit_name'=>$this->UNIT[rand(0,1)],
            'packet_price'=>rand(10,25),
            'consumed_quantity'=>rand(1, 1000),
            'isactive'=>true,
            'tag'=>$this->TAGS[rand(0, 2)],
            'min_qty'=>rand(1, 3),
            'max_qty'=>rand(4, 10),
            'show_only_pack_price'=>rand(0,1)
        ];
    }
}
