<?php

// ? remember this related to config/app.php
// create same file name messages.php in es spanish,
// remember key must be all same along the the en, es, de
// got to index.blade.php

// to see changes, change locale to es in app.php config folder
return [
    // "hello" key
    "hello" => "Hola world!",
    "example_with_value" => "Hola :name", // :name parameter

    // | pipe mean or condition
    // specify exact value should use this charater {}, :count parameter
    //  {0} it return No Comment yet, use trans_choice()
    //  and |[1,2] value will return like 1 comments or 2 comments
    // |2,*] 2 and infinity
    // * remember has array bracket [] mean specify the range
    "plural" => "{0} No commentarious yet|{1} :count commentarios|[2,*] :count commentarios :a",
    'people.reading' => '{0} Actualmente leído por nadie|{1} Actualmente leído por :count persona|[2,*] Actualmente leído por :count personas',
    'comments' => '{0} Sin comentarios aún|{1} :count comentario|[2,*] :count comentarios'
]
?>
