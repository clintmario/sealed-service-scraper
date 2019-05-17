<?php

namespace App\Modules\MITOCW\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MITOCWModel extends Model
{
    //
    public function insertContact($contact)
    {
        DB::insert("INSERT INTO contacts(name, email, subject, message, created_at, updated_at)
          VALUES(:name, :email, :subject, :message, NOW(), NOW())", $contact);
    }
}
