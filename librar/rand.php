<?php
class rand_for_dice
{
    public function god()
    {
        $d = mt_rand(0, 1000000);
        return $d;
    }

    public function dice($dice_num, $small, $big, $bouns, $Magnification)
    {
        $default_dice['end'] = 0;
        for ($j = 0; $j < $dice_num; $j++) {
            $dice = mt_rand($small, $big);
            $default_dice[] = $dice;
            if ($default_dice['end'] < $dice) {
                $default_dice['end'] = $dice;
            }
            $default_dice['end'] += ceil($bouns * $Magnification);
        }

        return $default_dice;
    }

}
/*

echo "<PRE>";
print_r($report_select);
echo "</PRE>";

$test = new rand_for_dice();
echo "<PRE>";
print_r($test->dice(3, 0, 100, 3, 1.5));
echo "</PRE>";
 */
