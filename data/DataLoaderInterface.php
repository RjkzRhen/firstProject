<?php

namespace data; // Определяем пространство имен для интерфейса

// Интерфейс для загрузки данных
interface DataLoaderInterface
{
    public function loadData(): void; // Объявляем метод loadData, который должен быть реализован в классах, реализующих этот интерфейс
}