<?php

namespace App\Migrations;

interface MigratorInterface
{
    public static function tableName(): string;
    public static function migrate(): void;
}
