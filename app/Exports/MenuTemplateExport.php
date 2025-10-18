<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MenuTemplateExport implements FromArray, WithHeadings
{
    /**
    * @return array
    */
    public function array(): array
    {
        return [
            [
                'Pizza Margherita',
                12.99,
                'Main Course',
                'PIZZA001',
                'Classic pizza with tomato sauce, mozzarella, and basil',
                'true',
                'true'
            ],
            [
                'Chicken Burger',
                8.99,
                'Burgers',
                'BURGER001',
                'Juicy chicken patty with lettuce, tomato, and mayo',
                'false',
                'true'
            ],
            [
                'Caesar Salad',
                7.50,
                'Salads',
                'SALAD001',
                'Fresh romaine lettuce with caesar dressing and croutons',
                'true',
                'true'
            ]
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Item Name',
            'Price',
            'Category',
            'SKU',
            'Description',
            'Is Vegetarian',
            'Is Active'
        ];
    }
}
