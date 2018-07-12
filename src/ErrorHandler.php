<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 09.07.18
 * Time: 12:37
 */

namespace Src;


class ErrorHandler
{
    protected $debug = false;

    public function setDebugMode(bool $debug)
    {
        $this->debug = $debug;
    }

    public function errorHandler($errno, $message, $file, $line)
    {
        $errors = array(
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
        );

        if($this->debug) {
            // TODO: pretty view
            echo "<b>" . $errors[$errno] ."[" . $errno . "]" ."</b>";
            echo "<p>" .  $message . "</p>";
            echo "<p>" .  $file . ":" . $line . "</p>";
            die;
        } else {
            // TODO: logging
        }


        return true;
    }

    public function fatalErrorHandler()
    {
        // если была ошибка и она фатальна
        if ($error = error_get_last() AND $error['type'] & ( E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR))
        {
            ob_end_clean();
            $this->errorHandler($error['type'], $error['message'], $error['file'], $error['line']);
        }
        else
        {
            // отправка (вывод) буфера и его отключение
            ob_end_flush();
        }
    }



    public function register()
    {
        set_error_handler([$this, 'errorHandler']);
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }
}