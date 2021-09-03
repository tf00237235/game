<?php
class pokerClass
{
    protected $flow = array("A", "B", "C", "D");
    protected $num = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K");
    protected $poke = array();

    public function set_poker_num()
    {
        for ($d = 0; $d < 4; $d++) {
            for ($j = 0; $j < 4; $j++) {
                for ($k = 0; $k < 13; $k++) {
                    $this->poke[] = $this->flow[$j] . $this->num[$k];
                }
            }
        }
    }
    public function return_poker()
    {
        $poke_echo = array_rand($this->poke);
        $poke_return = $this->poke[$poke_echo];
        unset($this->poke[$poke_echo]);
        return $poke_return;
    }
}

$test = new pokerClass();
$test->set_poker_num();

for ($j = 0; $j < 208; $j++) {
    echo "<PRE>";
    print_r($test->return_poker());
    echo "</PRE>";
}
