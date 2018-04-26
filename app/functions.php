<?php
/**
 * 自定义的一些方法
 */
// 专用调试方法（请自动加载）
if (! function_exists('gwc')) {
    function gwc()
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // SQL日志记录
        dblog();

        // TODO 待续
    }
}

// 调试日志输出专用 (带文件路径，断点)
if (! function_exists('ll')) {
    function ll()
    {
        $backtrace = debug_backtrace();
        $content   = @$_SERVER['REQUEST_URI'].PHP_EOL;
        $content  .= $backtrace[0]['file'].':'.$backtrace[0]['line'].PHP_EOL;
        $content  .= var_export($backtrace[0]['args'], true).PHP_EOL;
        logger($content);
    }
}

// 调试日志输出专用 (极简)
if (! function_exists('l')) {
    function l()
    {
        $backtrace = debug_backtrace();
        foreach ($backtrace[0]['args'] as $single) {
            if (is_scalar($single)) {
                logger($single);
            } else {
                ob_start();
                var_dump($single);
                $data = ob_get_clean();
                logger($data);
            }
        }
    }
}

// 记录DB操作日志
if (! function_exists('dblog')) {
    function dblog() {
        DB::listen(
            function ($sql) {
                foreach ($sql->bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $sql->bindings[$i] = "'$binding'";
                        }
                    }
                }
                $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
                $query = vsprintf($query, $sql->bindings);
                logger($query);
            }
        );
    }
}
