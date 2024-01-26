<?php

use UrlShortenerPhp\Base\View\View;
use UrlShortenerPhp\Session\Session;

function base_url()
{
  return APP_URL;
}
function route($to)
{
  return base_url() . $to;
}
function redirect($url, $mensajes = [])
{
  $data = [];
  $params = '';

  foreach ($mensajes as $key => $value) {
    array_push($data, $key . '=' . $value);
  }
  $params = join('&', $data);

  if ($params != '') {
    $params = '?' . $params;
  }
  header('Location: ' . base_url() . "{$url}{$params}");
  exit();
}
function encrypt($value)
{
  return base64_encode($value);
}
function redirectEncode($url, $mensajes = [])
{
  $data = [];
  $params = '';

  foreach ($mensajes as $key => $value) {
    $data[] = $key . '=' . encrypt($value);
  }

  $params = join('&', $data);

  if ($params != '') $params = '?' . $params;

  header('Location: ' . base_url() . "{$url}{$params}");
  exit();
}
function existsUserSession()
{
  return isset($_SESSION["userId"]);
}
function getSession(string $key)
{
  return Session::get($key);
}
function getFlash(string $key)
{
  return Session::getFlash($key);
}
function assets($uri_asset): string
{
  return PUBLIC_ASSETS . $uri_asset;
}
function view(string $template, array $data = [])
{
  return (new View)->render($template, $data);
}
function renderApp(string $template, array $data = [])
{
  return (new View)->renderInApp($template, $data);
}
function dd($varData)
{
  dump($varData);
  die;
}

function dump($data)
{
  echo "<pre style='background-color: #191919;padding: 20px; color: white; font-size:15px'>";
  var_dump($data);
  echo "</pre>";
  echo "<br>";
}
