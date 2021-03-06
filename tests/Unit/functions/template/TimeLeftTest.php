<?php


use PHPUnit\Framework\TestCase;


class TimeLeftTest extends TestCase
{

    public function testTimeLeft()
    {
        $currentDate = '2022-03-01 12:30';
        $finishDate = '2022-03-02';
        $result = timeLeft($finishDate, $currentDate);
        $this->assertEquals(["11", "30"], $result);

        $finishDate = '2022-03-03';
        $result = timeLeft($finishDate, $currentDate);
        $this->assertEquals(["35", "30"], $result);

        $finishDate = '2022-02-03';
        $result = timeLeft($finishDate, $currentDate);
        $this->assertEquals(["00", "00"], $result);
    }

}
