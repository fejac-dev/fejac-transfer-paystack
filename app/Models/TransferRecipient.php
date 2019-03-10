<?php

namespace Mybakery;

use Illuminate\Database\Eloquent\Model;
use Unicodeveloper\Paystack\Paystack;
class TransferRecipient extends Model
{
    //
    protected $fillable = ["type", "name", "description", "currency", "domain", "recipient_code", "active", "id",  "createdAt", "updatedAt"];


}
