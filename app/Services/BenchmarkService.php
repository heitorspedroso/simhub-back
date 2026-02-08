<?php

namespace App\Services;

use Exception;

class BenchmarkService
{
    private static $timers = [];
    private static $results = [];

    public static function start($timerName)
    {
        self::$timers[$timerName] = [
            'start_time' => microtime(true),
            'start_memory' => memory_get_usage(),
            'start_peak_memory' => memory_get_peak_usage(true),
        ];
    }

    public static function stop($timerName)
    {
        if (!isset(self::$timers[$timerName])) {
            throw new Exception("Timer '{$timerName}' não foi iniciado.");
        }

        $startData = self::$timers[$timerName];
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        $endPeakMemory = memory_get_peak_usage(true);

        $elapsedTime = $endTime - $startData['start_time'];

        $memoryUsed = $endMemory - $startData['start_memory'];

        $peakMemoryUsed = $endPeakMemory - $startData['start_peak_memory'];

        self::$results[$timerName] = [
            'time' => $elapsedTime,
            'memory' => self::formatMemory($memoryUsed),
            'peak_memory' => self::formatMemory($peakMemoryUsed),
        ];

        unset(self::$timers[$timerName]);
    }

    public static function getResults()
    {
        return self::$results;
    }

    public static function reset()
    {
        self::$timers = [];
        self::$results = [];
    }

    public static function formatMemory($bytes)
    {
        if ($bytes < 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
