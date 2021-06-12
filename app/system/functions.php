<?php

function base_url($url = '')
{
    $base_ssl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $base_host = $_SERVER['HTTP_HOST'];
    $request_uri = ($url != '') ? '/' . $url : '';
    if (WEB_HOSTING) {
        return $base_ssl . '://' . $base_host . $request_uri;
    } else {
        return $base_ssl . '://' . $base_host . '/' . HTDOCS_FOLDER . $request_uri;
    }
}

function redirect($url = '', $base_url = true)
{
    $direct_to = ($base_url) ? base_url($url) : $url;
    header('location: ' . $direct_to);
}

function check_url($url, $auto_redirect = false)
{
    $url_split = explode('://', $url);
    if (WEB_HOSTING && $url_split[0] != 'https') {
        $get_header = @get_headers('https://' . $url_split[1]);
        if (!$get_header || $get_header[0] == 'HTTP/1.0 404 Not Found') {
            return false;
        } else {
            if ($auto_redirect) redirect('https://' . $url_split[1], false);
            return true;
        }
    }
    return true;
}
