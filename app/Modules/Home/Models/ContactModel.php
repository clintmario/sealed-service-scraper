<?php

namespace App\Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContactModel extends Model
{
    //
    public function insertContact($contact)
    {
        DB::insert("INSERT INTO contacts(name, email, subject, message, created_at, updated_at)
          VALUES(:name, :email, :subject, :message, NOW(), NOW())", $contact);
    }
}
