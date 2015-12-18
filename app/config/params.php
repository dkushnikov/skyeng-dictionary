<?php

use yii\helpers\Json;

return [
    'adminEmail' => 'admin@example.com',
    'dictionary' => Json::decode('
        {
            "apple": "яблоко",
            "peach": "персик",
            "orange": "апельсин",
            "grape": "виноград",
            "lemon": "лимон",
            "pineapple": "ананас",
            "watermelon": "арбуз",
            "coconut": "кокос",
            "banana": "банан",
            "pomelo": "помело",
            "strawberry": "клубника",
            "raspberry": "малина",
            "melon": "дыня",
            "apricot": "абрикос",
            "mango": "манго",
            "plum": "слива",
            "pear": "груша",
            "pomegranate": "гранат",
            "cherry": "вишня"
        }
    '
    ),
];
