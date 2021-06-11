<?php

class home extends Controller
{
    public function index()
    {
        echo base_url('home/index');

        var_dump($_SERVER);
    }
}
