<?php

class controller
{
    protected function view($view, $data = [])
    {
        $array_keys = array_keys($data);
        foreach ($array_keys as $ark) {
            ${$ark} = $data[$ark];
        }
        require_once 'app/view/' . $view . '.php';
    }

    protected function model($model = 'basic_model')
    {
        require_once 'app/model/' . $model . '.php';
        return new $model;
    }

    protected function helper($helper, $param = '')
    {
        require_once 'app/helper/' . $helper . '.php';
        return new $helper($param);
    }
}
