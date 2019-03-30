<?php

namespace App\Http\Controllers;

use App\Helpers\Points;
use Alexo\LaravelAgileCRM\LaravelAgileCRM;
use Illuminate\Http\Request;

class AgileController extends Controller
{
    public static function createCompany($company)
    {
        $company_json = [
            'type' => 'COMPANY',
            'properties' => [
                [
                    'name' => 'name',
                    'value' => $company->name,
                    'type' => 'SYSTEM'
                ],
                [
                    'name' => 'NIT',
                    'value' => $company->nit,
                    'type' => 'CUSTOM'
                ],
            ]
        ];

        return LaravelAgileCRM::createCompany($company_json);
    }

    public static function createCustomer($customer)
    {
        $address = [
            'address' => $customer->address,
            'city' => $customer->city_name,
            'state' => $customer->department,
            'country' => 'Colombia'
        ];

        $contact_json = [
            'lead_score' => $customer->points,
            'star_value' => '5',
            'properties' => [
                [
                    'name' => 'first_name',
                    'value' => $customer->first_name,
                    'type' => 'SYSTEM'
                ],
                [
                    'name' => 'last_name',
                    'value' => $customer->last_name,
                    'type' => 'SYSTEM'
                ],
                [
                    'name' => 'surname',
                    'value' => $customer->surname,
                    'type' => 'CUSTOM'
                ],
                [
                    'name' => 'company',
                    'value' => $customer->company,
                    'type' => 'SYSTEM'
                ],
                [
                    'name' => 'email',
                    'value' => $customer->email,
                    'type' => 'SYSTEM'
                ],
                [
                    'name' => 'title',
                    'value' => 'Asesor de ventas',
                    'type' => 'SYSTEM'
                ],
                [
                    'name' => 'address',
                    'value' => json_encode($address),
                    'type' => 'SYSTEM'
                ],
                [
                    'name' => 'phone',
                    'value' => $customer->cellphone,
                    'type' => 'SYSTEM'
                ],
                [
                    'name' => 'identification',
                    'value' => $customer->identification,
                    'type' => 'CUSTOM'
                ],
                [
                    'name' => 'gender',
                    'value' => $customer->gender,
                    'type' => 'CUSTOM'
                ],
                [
                    'name' => 'zone',
                    'value' => $customer->zone,
                    'type' => 'CUSTOM'
                ]
            ]
        ];

        return LaravelAgileCRM::createContact($contact_json);
    }

    public static function updateCustomer($customer)
    {
        if (!is_null($customer->agile_id)) {
            if (self::fetchContact($customer->agile_id) != '') {
                $address = [
                    'address' => $customer->address,
                    'city' => $customer->city_name,
                    'state' => $customer->department,
                    'country' => 'Colombia'
                ];

                $contact_json = [
                    'id' => $customer->agile_id,
                    'properties' => [
                        [
                            'name' => 'first_name',
                            'value' => $customer->first_name,
                            'type' => 'SYSTEM'
                        ],
                        [
                            'name' => 'last_name',
                            'value' => $customer->last_name,
                            'type' => 'SYSTEM'
                        ],
                        [
                            'name' => 'surname',
                            'value' => $customer->surname,
                            'type' => 'CUSTOM'
                        ],
                        [
                            'name' => 'company',
                            'value' => $customer->company,
                            'type' => 'SYSTEM'
                        ],
                        [
                            'name' => 'email',
                            'value' => $customer->email,
                            'type' => 'SYSTEM'
                        ],
                        [
                            'name' => 'title',
                            'value' => 'Asesor de ventas',
                            'type' => 'SYSTEM'
                        ],
                        [
                            'name' => 'address',
                            'value' => json_encode($address),
                            'type' => 'SYSTEM'
                        ],
                        [
                            'name' => 'phone',
                            'value' => $customer->cellphone,
                            'type' => 'SYSTEM'
                        ],
                        [
                            'name' => 'identification',
                            'value' => $customer->identification,
                            'type' => 'CUSTOM'
                        ],
                        [
                            'name' => 'gender',
                            'value' => $customer->gender,
                            'type' => 'CUSTOM'
                        ],
                        [
                            'name' => 'zone',
                            'value' => $customer->zone,
                            'type' => 'CUSTOM'
                        ]
                    ]
                ];

                return LaravelAgileCRM::update($contact_json);
            }
        }
    }

    public static function updateCustomerScore($customer)
    {
        if (!is_null($customer->agile_id)) {
            $contactScore = [
                'id' => $customer->agile_id,
                'lead_score' => Points::getUserPointsTotal($customer->id)
            ];

            return LaravelAgileCRM::updateLeadScore($contactScore);
        }
    }

    public static function fetchContact($agileId)
    {
        return LaravelAgileCRM::find($agileId);
    }
}
