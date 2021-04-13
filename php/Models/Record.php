<?php
namespace SuperHeroList\Models;

class Record extends ActiveRecord
{
    protected static $table = 'records';
    
    public ?string $name;
    public ?string $surname;
    public ?string $fathername;
    public ?string $registration_number;
    public ?string $power_description;
    public ?string $category;
    public ?string $subtype;
}