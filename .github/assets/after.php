<?php

$anotherArray = [ /* ... */];


$myResult = $order->export([
    'id',
    'items' => ['*' => ['quantity', 'price', 'price_ht', 'vat', 'product' => 'name']],
    'client' => ['first_name', 'last_name', 'cards.*.brand as cards_brands'],
    'created_at',
    'delivered_at',
    'total_price',
    'total_quantity',
])->merge($anotherArray)->toJson();
