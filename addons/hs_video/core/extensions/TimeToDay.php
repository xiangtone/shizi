<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/1
 * Time: 9:08
 */

namespace app\extensions;


class TimeToDay
{
    public static function time($time)
    {
        $h = floor($time / 3600);
        $h_s = $time % 3600;
        $i = floor($h_s / 60);
        $i_s = $h_s % 60;
        $s = ceil($i_s);
        if ($time >= 3600) {
            return self::tow($h) . ':' . self::tow($i) . ':' . self::tow($s);
        } else {
            return self::tow($i) . ':' . self::tow($s);
        }
    }

    public function tow($time)
    {
        if (strlen($time) < 2) {
            return '0' . $time;
        } else {
            return $time;
        }
    }

    public static function getPageView($page_view)
    {
        if ($page_view > 10000) {
            $page_view = round($page_view / 10000, 2);
            return $page_view . 'W';
        } else {
            return $page_view;
        }
    }

    public static function date($time)
    {
        $h = floor($time / 3600);
        $h_s = $time % 3600;
        $i = floor($h_s / 60);
        $i_s = $h_s % 60;
        $s = ceil($i_s);
        if ($time >= 3600) {
            return self::tow($h) . '小时' . self::tow($i) . '分钟' . self::tow($s) . '秒';
        } else if ($time >= 60) {
            return self::tow($i) . '分钟' . self::tow($s).'秒';
        }else{
            return self::tow($s).'秒';
        }
    }
}