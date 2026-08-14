<?php
/**
 * Bengali Calendar & Date Converter Helper Class for Mughdo Newspaper Theme
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProthomNews_Bangla_Date {

    private static $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
    private static $en_digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

    private static $bn_months = array(
        'বৈশাখ', 'জ্যৈষ্ঠ', 'আষাঢ়', 'শ্রাবণ', 'ভাদ্র', 'আশ্বিন',
        'কার্তিক', 'অগ্রহায়ণ', 'পৌষ', 'মাঘ', 'ফাল্গুন', 'চৈত্র'
    );

    private static $bn_weekdays = array(
        'Sunday'    => 'রবিবার',
        'Monday'    => 'সোমবার',
        'Tuesday'   => 'মঙ্গলবার',
        'Wednesday' => 'বুধবার',
        'Thursday'  => 'বৃহস্পতিবার',
        'Friday'    => 'শুক্রবার',
        'Saturday'  => 'শনিবার'
    );

    private static $en_months_bn = array(
        'January'   => 'জানুয়ারি',
        'February'  => 'ফেব্রুয়ারি',
        'March'     => 'মার্চ',
        'April'     => 'এপ্রিল',
        'May'       => 'মে',
        'June'      => 'জুন',
        'July'      => 'জুলাই',
        'August'    => 'আগস্ট',
        'September' => 'সেপ্টেম্বর',
        'October'   => 'অক্টোবর',
        'November'  => 'নভেম্বর',
        'December'  => 'ডিসেম্বর'
    );

    /**
     * Convert English numbers to Bengali numbers
     */
    public static function convert_number($number) {
        return str_replace(self::$en_digits, self::$bn_digits, (string)$number);
    }

    /**
     * Get Current Date in Bengali (Alias)
     */
    public static function get_current_bangla_date($timestamp = null) {
        return self::get_gregorian_bn($timestamp);
    }

    /**
     * Get English Gregorian Date in Bengali Language
     * Example: শুক্রবার, ১৪ আগস্ট ২০২৬
     */
    public static function get_gregorian_bn($timestamp = null) {
        if (!$timestamp) {
            $timestamp = current_time('timestamp');
        }

        $weekday_en = date('l', $timestamp);
        $day        = date('d', $timestamp);
        $month_en   = date('F', $timestamp);
        $year       = date('Y', $timestamp);

        $weekday_bn = isset(self::$bn_weekdays[$weekday_en]) ? self::$bn_weekdays[$weekday_en] : $weekday_en;
        $month_bn   = isset(self::$en_months_bn[$month_en]) ? self::$en_months_bn[$month_en] : $month_en;

        return sprintf(
            '%s, %s %s %s',
            $weekday_bn,
            self::convert_number($day),
            $month_bn,
            self::convert_number($year)
        );
    }

    /**
     * Calculate Bengali Calendar Date (Bangla Standard Revision)
     * Returns string like: ২৯ শ্রাবণ ১৪৩৩
     */
    public static function get_bangla_calendar_date($timestamp = null) {
        if (!$timestamp) {
            $timestamp = current_time('timestamp');
        }

        $day   = (int) date('d', $timestamp);
        $month = (int) date('m', $timestamp);
        $year  = (int) date('Y', $timestamp);

        // Leap year check
        $is_leap_year = (($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0);

        // Days in months in revised Bengali Calendar
        $bn_month_days = array(31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 30, 30);
        if ($is_leap_year) {
            $bn_month_days[10] = 31;
        }

        // Bengali year calculation (Bangla Era starting from 593 AD)
        $bn_year = $year - 593;
        if ($month < 4 || ($month == 4 && $day < 14)) {
            $bn_year--;
        }

        // Month and day determination
        $bn_month_index = 0;
        $bn_day = 1;

        if ($month == 4 && $day >= 14) {
            $bn_month_index = 0;
            $bn_day = $day - 13;
        } elseif ($month == 4 && $day < 14) {
            $bn_month_index = 11;
            $bn_day = $day + 16;
        } elseif ($month == 5) {
            if ($day <= 14) {
                $bn_month_index = 0;
                $bn_day = $day + 17;
            } else {
                $bn_month_index = 1;
                $bn_day = $day - 14;
            }
        } elseif ($month == 6) {
            if ($day <= 14) {
                $bn_month_index = 1;
                $bn_day = $day + 17;
            } else {
                $bn_month_index = 2;
                $bn_day = $day - 14;
            }
        } elseif ($month == 7) {
            if ($day <= 15) {
                $bn_month_index = 2;
                $bn_day = $day + 16;
            } else {
                $bn_month_index = 3;
                $bn_day = $day - 15;
            }
        } elseif ($month == 8) {
            if ($day <= 15) {
                $bn_month_index = 3;
                $bn_day = $day + 16;
            } else {
                $bn_month_index = 4;
                $bn_day = $day - 15;
            }
        } elseif ($month == 9) {
            if ($day <= 15) {
                $bn_month_index = 4;
                $bn_day = $day + 16;
            } else {
                $bn_month_index = 5;
                $bn_day = $day - 15;
            }
        } elseif ($month == 10) {
            if ($day <= 15) {
                $bn_month_index = 5;
                $bn_day = $day + 15;
            } else {
                $bn_month_index = 6;
                $bn_day = $day - 15;
            }
        } elseif ($month == 11) {
            if ($day <= 14) {
                $bn_month_index = 6;
                $bn_day = $day + 16;
            } else {
                $bn_month_index = 7;
                $bn_day = $day - 14;
            }
        } elseif ($month == 12) {
            if ($day <= 14) {
                $bn_month_index = 7;
                $bn_day = $day + 16;
            } else {
                $bn_month_index = 8;
                $bn_day = $day - 14;
            }
        } elseif ($month == 1) {
            if ($day <= 13) {
                $bn_month_index = 8;
                $bn_day = $day + 17;
            } else {
                $bn_month_index = 9;
                $bn_day = $day - 13;
            }
        } elseif ($month == 2) {
            if ($day <= 12) {
                $bn_month_index = 9;
                $bn_day = $day + 18;
            } else {
                $bn_month_index = 10; // Falgun
                $bn_day = $day - 12;
            }
        } elseif ($month == 3) {
            if ($day <= 14) {
                $bn_month_index = 10; // Falgun
                $bn_day = $day + 16;
            } else {
                $bn_month_index = 11; // Chaitra
                $bn_day = $day - 14;
            }
        }

        $month_name = self::$bn_months[$bn_month_index];

        return sprintf(
            '%s %s %s',
            self::convert_number($bn_day),
            $month_name,
            self::convert_number($bn_year)
        );
    }
}
