<?php

namespace data;

// ��������� ��� �������� ������
interface DataLoaderInterface
{
    public function loadData($source): void;
}