<?php

class Message
{
    private static $template = '<div id="message"><p>{{ message }}</p></div>';

    static public function set($msg)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['MESSAGE'] = $msg;
    }

    static public function get()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['MESSAGE'])) {
            return '';
        }
        $msg = $_SESSION['MESSAGE'];
        unset($_SESSION['MESSAGE']);
        return str_replace('{{ message }}', $msg, self::$template);
    }

    static public function setRedirect()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            $uri = parse_url($_SERVER['HTTP_REFERER']);

            if (isset($uri['path'])) {
                $path = $uri['path'];
            }
            if (isset($uri['query'])) {
                $path .= '?' . $uri['query'];
            }
            $path = ltrim($path, '/');
        } else {
            $path = '';
        }

        $_SESSION['REDIRECT'] = $path;
    }

    static public function getRedirect()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['REDIRECT'])) {
            return '';
        }
        $path = $_SESSION['REDIRECT'];
        unset($_SESSION['REDIRECT']);
        return $path;
    }
}
