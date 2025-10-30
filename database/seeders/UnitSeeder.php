<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ConfigurationProfile;
use App\Models\Unit;
use App\Models\UnitConfigurationValue;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $profile = ConfigurationProfile::where('slug', 'default-server-profile')->with('fields')->first();

        $units = [
            [
                'name' => 'Compute Alpha',
                'description' => 'Server rack 1U dengan prosesor AMD EPYC generasi terbaru.',
                'status' => 'available',
                'price_per_day' => 350_000,
                'penalty' => 80_000,
                'ip_address' => '192.168.10.10',
                'location' => 'Jakarta DC',
                'categories' => ['dedicated-server'],
                'configuration' => [
                    'vcpu' => '8',
                    'ram' => '32 GB',
                    'storage' => '1 TB SSD',
                    'bandwidth' => '1 Gbps',
                ],
            ],
            [
                'name' => 'Compute Beta',
                'description' => 'VPS premium dengan storage full NVMe dan redundansi tinggi.',
                'status' => 'available',
                'price_per_day' => 180_000,
                'penalty' => 50_000,
                'ip_address' => '192.168.10.20',
                'location' => 'Surabaya DC',
                'categories' => ['virtual-private-server'],
                'configuration' => [
                    'vcpu' => '4',
                    'ram' => '16 GB',
                    'storage' => '512 GB NVMe',
                    'bandwidth' => '500 Mbps',
                ],
            ],
            [
                'name' => 'Colo Prime',
                'description' => 'Slot colocation dengan daya 1.5kVA dan konektivitas 10Gbps.',
                'status' => 'available',
                'price_per_day' => 220_000,
                'penalty' => 60_000,
                'ip_address' => null,
                'location' => 'Bandung DC',
                'categories' => ['colocation'],
                'configuration' => [
                    'vcpu' => 'N/A',
                    'ram' => 'N/A',
                    'storage' => 'N/A',
                    'bandwidth' => '10 Gbps',
                ],
            ],
        ];

        foreach ($units as $unitData) {
            $categories = $unitData['categories'] ?? [];
            $configuration = $unitData['configuration'] ?? [];

            unset($unitData['categories'], $unitData['configuration']);

            $unitAttributes = array_merge($unitData, [
                'configuration_profile_id' => $profile?->id,
            ]);

            $unit = Unit::updateOrCreate(
                ['name' => $unitAttributes['name']],
                $unitAttributes
            );

            if ($categories) {
                $categoryIds = Category::whereIn('slug', $categories)->pluck('id')->all();
                $unit->categories()->sync($categoryIds);
            }

            if ($profile && $configuration) {
                foreach ($profile->fields as $field) {
                    $value = $configuration[$field->key] ?? $field->default_value;

                    UnitConfigurationValue::updateOrCreate(
                        [
                            'unit_id' => $unit->id,
                            'configuration_field_id' => $field->id,
                        ],
                        ['value' => $value]
                    );
                }
            }
        }
    }
}
