<?php

namespace Database\Seeders;

use App\Models\ConfigurationField;
use App\Models\ConfigurationProfile;
use Illuminate\Database\Seeder;

class ConfigurationProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            [
                'name' => 'Default Server Profile',
                'slug' => 'default-server-profile',
                'description' => 'Konfigurasi standar untuk layanan sewa server.',
                'fields' => [
                    [
                        'label' => 'vCPU',
                        'key' => 'vcpu',
                        'type' => 'number',
                        'default_value' => '4',
                        'is_required' => true,
                        'options' => null,
                        'meta' => ['unit' => 'core'],
                    ],
                    [
                        'label' => 'RAM',
                        'key' => 'ram',
                        'type' => 'text',
                        'default_value' => '16 GB',
                        'is_required' => true,
                        'options' => null,
                        'meta' => ['unit' => 'GB'],
                    ],
                    [
                        'label' => 'Storage',
                        'key' => 'storage',
                        'type' => 'text',
                        'default_value' => '512 GB SSD',
                        'is_required' => true,
                        'options' => null,
                        'meta' => ['unit' => 'GB'],
                    ],
                    [
                        'label' => 'Bandwidth',
                        'key' => 'bandwidth',
                        'type' => 'text',
                        'default_value' => '500 Mbps',
                        'is_required' => false,
                        'options' => null,
                        'meta' => ['unit' => 'Mbps'],
                    ],
                ],
            ],
        ];

        foreach ($profiles as $profileData) {
            $fields = $profileData['fields'];
            unset($profileData['fields']);

            $profile = ConfigurationProfile::updateOrCreate(
                ['slug' => $profileData['slug']],
                $profileData
            );

            foreach ($fields as $fieldData) {
                ConfigurationField::updateOrCreate(
                    [
                        'configuration_profile_id' => $profile->id,
                        'key' => $fieldData['key'],
                    ],
                    array_merge($fieldData, ['configuration_profile_id' => $profile->id])
                );
            }
        }
    }
}
