<?php

namespace data;

// Интерфейс для загрузки данных
interface DataLoaderInterface
{
    public function loadData($source): void;
}