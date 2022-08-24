<?php

namespace App\Services;

use App\Exceptions\Auth\AuthException;
use App\Models\Contact;
use App\Models\User;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserContactService
{

    public function createContact(string $type, string $value): Contact
    {
        $new_contact = new Contact([
            'type' => $type,
            'contact' => $value
        ]);
        return $new_contact;
    }

    public function updateContact(Contact $contact, string $type, string $value): bool
    {
        $contact->type = $type;
        $contact->contact = $value;

        return $contact->save();
    }

    public function assignContactToUser(User $user, Contact $contact): bool
    {
        $contact->user_id = $user->id;
        return $contact->save();
    }


}
