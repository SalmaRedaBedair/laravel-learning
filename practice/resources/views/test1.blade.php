<?php
App\Models\Contact::chunk(100000, function ($contacts) {
    foreach ($contacts as $contact) {
        echo $contact;
    }
});


// $model = App\Models\Contact::make([
//     'phone'=>'kdnskn'
// ]);
// echo $model;
// $model->save();
// echo $model;

// $contact = App\Models\Contact::firstOrNew(['phone' => 'luis.ramos@acme.com']);
// $contact->save();
// echo $contact;
?>
