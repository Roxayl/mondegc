<?php

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\Pays;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class OrganisationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organisation::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'logo' => '',
            'flag' => null,
            'text' => $this->faker->text(150),
            'type' => 'organisation',
            'allow_temperance' => false,
            'type_migrated_at' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Organisation $organisation) {
            DB::table('organisation_members')
                ->insert([
                    'organisation_id' => $organisation->id,
                    'pays_id'         => Pays::inRandomOrder()->first()->ch_pay_id,
                    'permissions'     => Organisation::PERMISSION_OWNER,
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
        });
    }
}
