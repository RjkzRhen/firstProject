<?php

namespace config;

class Page
{
    public const  LINK_HOMEPAGE = '/';
    public const  LINK_TABLE = '/table';
    public const  LINK_FORM = '/form';
    public const  LINK_CSV = '/csv';
    public const  LINK_CSV_FORM = '/add_record';


    public const  LINKS = [
        'Домашняя страница' => self::LINK_HOMEPAGE,
        'Таблица' => self::LINK_TABLE,
        'Форма добавления в таблицу' => self::LINK_FORM,
        'CSV Таблица' => self::LINK_CSV,
        'CSV Форма' => self::LINK_CSV_FORM,

    ];
}