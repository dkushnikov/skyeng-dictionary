<?php

use yii\helpers\Json;

return [
    'adminEmail' => 'admin@example.com',
    'dictionary' => Json::decode('
        {
            "apple": "яблоко",
            "pear": "персик",
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
            "pear": "слива",
            "pomegranate": "гранат",
            "cherry": "вишня"
        }
    '
    ),
];
