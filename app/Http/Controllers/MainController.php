<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        echo 'Index ativo';
    }

    public function newNote()
    {
        echo 'new note';
    }
}
