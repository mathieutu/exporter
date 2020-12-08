<?php

$anotherArray = [ /* ... */];

$myResult = json_encode(array_merge([
    'id' => $order->id,
    'items' => array_map(function ($item) {
        return [
            'quantity' => $item->quantity,
            'price' => $item->price,
            'price_ht' => $item->price_ht,
            'vat' => $item->vat,
            'product' => $item->product->name,
        ];
    }, $order->items),
    'client' => [
        'first_name' => $order->client->first_name,
        'last_name' => $order->client->last_name,
        'cards_brands' => array_map(function ($card) {
            return $card['brand'];
        }, $order->client->cards),
    ],

    'created_at' => $order->created_at,
    'delivered_at' => $order->delivered_at,
    'total_price' => $order->getTotalPrice(),
    'total_quantity' => $order->getTotalQuantity(),
], $anotherArray));
