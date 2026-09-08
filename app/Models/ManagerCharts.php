<?php

declare(strict_types=1);

final class ManagerCharts
{
    private const BLUE = '#1B54B8';
    private const TEAL = '#0E7C78';
    private const GREEN = '#1EA672';
    private const AMBER = '#E0A63B';
    private const RED = '#C03036';
    private const SLATE = '#5A6B8C';

    public const PERIODS = [7 => 'Last 7 days', 30 => 'Last 30 days', 90 => 'Last 90 days', 365 => 'Last 12 months'];

    private const DOCTORS = ['Dr. Sample Doctor 1', 'Dr. Sample Doctor 3', 'Dr. Sample Doctor 2'];
    private const WEEKDAYS = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    private const HOURS = ['8a', '9a', '10a', '11a', '12p', '1p', '2p', '3p', '4p', '5p', '6p'];
    private const WEEKS_8 = ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8'];

    public static function dashboard(): array
    {
        $week = self::periodLabels(7);

        return [
            'dash-appointments-trend' => self::chart('line', $week, [
                self::series('Booked', self::BLUE, [38, 12, 44, 41, 47, 43, 42]),
                self::series('Completed', self::GREEN, [34, 10, 40, 37, 42, 39, 38]),
            ]),
            'dash-revenue-trend' => self::chart('line', $week, [
                self::series('Revenue', self::TEAL, [72, 41, 88, 79, 96, 84, 96]),
            ], ['suffix' => 'k']),
            'dash-doctor-workload' => self::chart('bar', self::DOCTORS, [
                self::series('Patients', self::BLUE, [24, 12, 10]),
            ]),
            'dash-queue-performance' => self::chart('line', self::HOURS, [
                self::series('Avg wait', self::AMBER, [6, 9, 18, 22, 14, 11, 16, 20, 12, 8, 5]),
            ], ['suffix' => 'm']),
        ];
    }

    public static function overview(int $period): array
    {
        $labels = self::periodLabels($period);
        $points = count($labels);

        return [
            'ov-revenue-trend' => self::chart('line', $labels, [
                self::series('Consultation', self::BLUE, self::wave($points, 60, 120)),
                self::series('Pharmacy', self::TEAL, self::wave($points, 40, 95)),
            ], ['suffix' => 'k']),
            'ov-patients-weekday' => self::chart('bar', self::WEEKDAYS, [
                self::series('Patients', self::BLUE, [52, 61, 58, 55, 63, 44, 0]),
            ]),
        ];
    }

    public static function appointments(int $period): array
    {
        $labels = self::periodLabels($period);
        $points = count($labels);

        return [
            'ap-trend' => self::chart('line', $labels, [
                self::series('Booked', self::BLUE, self::wave($points, 34, 48)),
                self::series('Completed', self::GREEN, self::wave($points, 28, 44)),
            ]),
            'ap-status-breakdown' => self::doughnut(
                ['Completed', 'Cancelled', 'No-show'],
                self::scale([1096, 88, 78], $period),
                [self::GREEN, self::AMBER, self::RED],
            ),
            'ap-by-doctor' => self::chart('bar', self::DOCTORS, [
                self::series('Appointments', self::BLUE, self::scale([118, 64, 52], $period)),
            ], ['horizontal' => true]),
            'ap-peak-hours' => self::chart('bar', self::HOURS, [
                self::series('Appointments', self::TEAL, self::scale([18, 34, 46, 40, 22, 15, 28, 38, 30, 20, 10], $period)),
            ]),
            'ap-peak-weekdays' => self::chart('bar', self::WEEKDAYS, [
                self::series('Appointments', self::BLUE, self::scale([212, 244, 231, 220, 248, 107, 0], $period)),
            ]),
            'ap-cancel-noshow' => self::chart('bar', self::WEEKS_8, [
                self::series('Cancelled', self::AMBER, [14, 12, 15, 11, 13, 10, 12, 9]),
                self::series('No-show', self::RED, [12, 11, 13, 10, 9, 11, 8, 7]),
            ]),
        ];
    }

    public static function revenue(int $period): array
    {
        $labels = self::periodLabels($period);
        $points = count($labels);

        return [
            'rev-trend' => self::chart('line', $labels, [
                self::series('Consultation', self::BLUE, self::wave($points, 60, 120)),
                self::series('Pharmacy', self::TEAL, self::wave($points, 40, 95)),
            ], ['suffix' => 'k']),
            'rev-breakdown' => self::doughnut(
                ['Consultation', 'Pharmacy', 'Procedures'],
                self::scale([1280, 1130, 96], $period),
                [self::BLUE, self::TEAL, self::AMBER],
            ),
            'rev-monthly' => self::chart('bar', self::periodLabels(365), [
                self::series('Revenue', self::BLUE, [1.9, 2.0, 2.1, 2.05, 2.2, 2.15, 2.3, 2.25, 2.35, 2.28, 2.4, 2.41]),
            ], ['suffix' => 'M']),
            'rev-per-patient' => self::chart('line', self::WEEKS_8, [
                self::series('Rs / patient', self::GREEN, [1850, 1920, 1880, 1960, 1990, 2010, 2000, 2035]),
            ], ['prefix' => 'Rs ', 'fromZero' => false]),
        ];
    }

    public static function queue(int $period): array
    {
        $labels = self::periodLabels($period);

        return [
            'q-avg-wait' => self::chart('line', $labels, [
                self::series('Avg wait (min)', self::TEAL, self::wave(count($labels), 16, 27)),
            ], ['suffix' => 'm']),
            'q-length' => self::chart('line', self::HOURS, [
                self::series('Waiting', self::AMBER, [2, 5, 12, 14, 9, 6, 8, 11, 7, 4, 2]),
            ]),
            'q-consult-duration' => self::chart('bar', self::DOCTORS, [
                self::series('Minutes', self::TEAL, [11, 13, 14]),
            ], ['suffix' => 'm']),
            'q-doctor-delay' => self::chart('bar', self::DOCTORS, [
                ['label' => 'Delay', 'colors' => [self::BLUE, self::TEAL, self::AMBER], 'data' => [5, 2, 12]],
            ], ['suffix' => 'm', 'horizontal' => true]),
        ];
    }

    public static function patients(int $period): array
    {
        $labels = self::periodLabels($period);

        return [
            'patient-find-new-returning' => self::chart('bar', self::periodLabels(365), [
                self::series('New', self::GREEN, [180, 190, 200, 195, 205, 210, 200, 208, 215, 210, 212, 214]),
                self::series('Returning', self::BLUE, [820, 860, 900, 880, 910, 940, 920, 950, 960, 955, 968, 970]),
            ], ['stacked' => true]),
            'patient-find-gender' => self::doughnut(
                ['Female', 'Male', 'Other'],
                [2064, 1798, 80],
                [self::BLUE, self::TEAL, self::SLATE],
            ),
            'patient-find-age' => self::chart('bar', ['0–12', '13–18', '19–35', '36–50', '51–65', '65+'], [
                self::series('Patients', self::BLUE, [412, 268, 1084, 940, 706, 532]),
            ]),
            'patient-find-growth' => self::chart('line', $labels, [
                self::series('Total patients', self::GREEN, self::rising(3600, 3942, count($labels))),
            ], ['fromZero' => false]),
        ];
    }

    public static function staff(): array
    {
        return [
            'st-doctor-seen' => self::chart('bar', self::DOCTORS, [
                ['label' => 'Patients seen', 'colors' => [self::BLUE, self::TEAL, self::AMBER], 'data' => [118, 64, 52]],
            ]),
            'st-reception' => self::chart('bar', ['Sandanu D.', 'Ashan C.'], [
                self::series('Registrations', self::BLUE, [96, 74]),
                self::series('Check-ins', self::TEAL, [412, 356]),
            ]),
        ];
    }

    public static function financial(): array
    {
        return [
            'financial-income-category' => self::doughnut(
                ['Consultation', 'Pharmacy', 'Procedures & other'],
                [1280, 1130, 96],
                [self::BLUE, self::TEAL, self::AMBER],
            ),
            'financial-channels' => self::doughnut(
                ['Cash', 'Online', 'Card'],
                [2125, 3025, 617],
                [self::SLATE, self::BLUE, self::TEAL],
            ),
        ];
    }

    private static function chart(string $type, array $labels, array $series, array $options = []): array
    {
        return ['type' => $type, 'labels' => $labels, 'series' => $series] + $options;
    }

    private static function series(string $label, string $color, array $data): array
    {
        return ['label' => $label, 'color' => $color, 'data' => $data];
    }

    private static function doughnut(array $labels, array $data, array $colors): array
    {
        return self::chart('doughnut', $labels, [['label' => '', 'colors' => $colors, 'data' => $data]]);
    }

    private static function periodLabels(int $period): array
    {
        $today = new DateTimeImmutable('today');
        $labels = [];

        if ($period === 365) {
            $month = $today->modify('first day of this month');
            for ($i = 11; $i >= 0; $i--) {
                $labels[] = $month->modify('-' . $i . ' months')->format('M');
            }
            return $labels;
        }
        if ($period === 90) {
            for ($i = 12; $i >= 0; $i--) {
                $labels[] = $today->modify('-' . $i . ' weeks')->format('j M');
            }
            return $labels;
        }

        $format = $period === 7 ? 'D' : 'j M';
        for ($i = $period - 1; $i >= 0; $i--) {
            $labels[] = $today->modify('-' . $i . ' days')->format($format);
        }
        return $labels;
    }

    private static function scale(array $numbers, int $period): array
    {
        $factor = [7 => 0.24, 30 => 1, 90 => 3, 365 => 12][$period];

        return array_map(fn ($number) => (int) round($number * $factor), $numbers);
    }

    private static function wave(int $count, int $min, int $max): array
    {
        $series = [];
        $seed = $count * 7 + $min;
        for ($i = 0; $i < $count; $i++) {
            $seed = ($seed * 9301 + 49297) % 233280;
            $random = $seed / 233280;
            $middle = $min + ($max - $min) * (0.45 + 0.35 * sin($i / 3));
            $series[] = (int) round($middle + ($max - $min) * 0.18 * ($random - 0.5));
        }
        return $series;
    }

    private static function rising(int $start, int $end, int $count): array
    {
        $series = [];
        for ($i = 0; $i < $count; $i++) {
            $series[] = (int) round($start + ($end - $start) * ($i / ($count - 1)));
        }
        return $series;
    }
}
