<?php

namespace data;

abstract class AbstractTable
{
    protected $data;

    abstract public function loadData($filePath): void;

    abstract public function getHtml(): string;

    protected function getStyle(): string
    {
        return "<style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .age-over-50 {
            color: red;
            font-weight: bold;
        }
        </style>";
    }
}