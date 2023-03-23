<?php


use PHPUnit\Framework\TestCase;

class TimeLeftTest extends TestCase
{

    /**
     * Метод тестирует функцию TimeLeft
     * @var string $finisDate
     * @return void
     */
    public function testTimeLeft(): void
    {
        $finishDate = '2022-03-02';
        $result = timeLeft($finishDate);
        $this->assertEquals(["00", "00"], $result);
    }

}
