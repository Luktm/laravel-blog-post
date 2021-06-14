<?php

// ? remember this related to config/app.php
// create same file name messages.php in es spanish
// key must be the same in different file
// see index.blade.php
return [
    "hello" => "Hello world!",
    "example_with_value" => "Hello :name", // name must be given see index.blade.php {{ _("example_with_value", ["name"] => "John") }}

     // | pipe mean or condition
    // specify exact value should use this charater {}, :count parameter
    //  {0} it return No Comment yet, use trans_choice()
    //  and |[1,2] value will return like 1 comments or 2 comments
    // |2,*] 2 and infinity
    // * remember has array bracket [] mean specify the range
    "plural" => "{0} No comments yet|{1} :count comments :a|[2,*] :count comments :a",
    'people.reading' => '{0} Currently read by :count nobody|{1} Currently read by :count person|[2,*] Currently read by :count people',
    'comments' => '{0} No comments yet|{1} :count comment|[2,*] :count comments'
]

?>
