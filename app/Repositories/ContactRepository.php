<?php

namespace App\Repositories;

use App\Models\Contact;
use App\RepositoryInterfaces\ContactRepositoryInterface;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    protected Contact $contact;
    public function __construct(Contact $contact)
    {
        parent::__construct($contact);
    }
}
