<?php
namespace data;

class TableStyles {
    public function getStyles(): string {
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
       form {
           margin-bottom: 20px;
       }
       label {
           font-weight: bold;
           margin-right: 10px;
       }
       input[type=\"number\"], input[type=\"submit\"] {
           padding: 8px;
           border: 1px solid #ccc;
           border-radius: 4px;
       }
       input[type=\"submit\"] {
           background-color: #4CAF50;
           color: white;
           cursor: pointer;
       }
       input[type=\"submit\"]:hover {
           background-color: #45a049;
       }
   </style>";
    }
}