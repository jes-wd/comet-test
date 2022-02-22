<?php

class EfficiencyScore {
    private static array $control_methods = [
        'roundabout' => [
            'high_throughput' => 50,
            'medium_throughput' => 75,
            'low_throughput' => 90
        ],
        'stop_signs' => [
            'high_throughput' => 20,
            'medium_throughput' => 30,
            'low_throughput' => 40
        ],
        'traffic_lights' => [
            'high_throughput' => 90,
            'medium_throughput' => 75,
            'low_throughput' => 30
        ],
    ];
    private static array $throughput_types = [
        'high_throughput' => [
            'from' => 20,
            'to' => 9999,
        ],
        'medium_throughput' => [
            'from' => 10,
            'to' => 20,
        ],
        'low_throughput' => [
            'from' => 0,
            'to' => 10,
        ],
    ];

    public static function get_efficiency_score() {
        $road_cpms = self::get_user_input_for_road_cpms();

        // get efficiency score for each control method
        foreach (self::$control_methods as $control_method_to_test => $values) {
            $efficiency_score = 0;
            
            foreach ($road_cpms as $road_cpm) {
                $throughput_type = self::get_throughput_type_for_road_cpm($road_cpm);
                $efficiency_score += self::$control_methods[$control_method_to_test][$throughput_type];
            }

            // get efficiency score percentage based on a total of 400 theoretically possible points
            $efficiency_score_percentage = $efficiency_score / 400 * 100;

            echo 'The ' . str_replace('_', ' ', $control_method_to_test) . ' control method has a total efficiency of ' . $efficiency_score_percentage . '% given the CPM of all roads.' . PHP_EOL;
        }
    }

    private static function get_throughput_type_for_road_cpm(int $road_cpm) {
        foreach (self::$throughput_types as $throughput_type => $throughput_range) {
            if ($road_cpm >= $throughput_range['from'] && $road_cpm <= $throughput_range['to']) {
                return $throughput_type;
            }
        }
    }

    private static function get_user_input_for_road_cpms() {
        $road_cpms = [];

        echo 'Enter the number of cars per minute on the north road: ';

        $road_cpms['north'] = trim(fgets(STDIN, 1024));

        echo 'Enter the number of cars per minute on the east road: ';

        $road_cpms['east'] = trim(fgets(STDIN, 1024));

        echo 'Enter the number of cars per minute on the south road: ';

        $road_cpms['south'] = trim(fgets(STDIN, 1024));

        echo 'Enter the number of cars per minute on the west road: ';

        $road_cpms['west'] = trim(fgets(STDIN, 1024));

        return $road_cpms;
    }
}

echo EfficiencyScore::get_efficiency_score();
