
<?php

/**
 * PHP日历类
 *
 * Class Calendar
 *
 * @author Machengdong
 *
 * @date 2017-08-21 23:00
 */

class Calendar{

    protected $_y;//当年

    protected $_m;//当月

    protected $_d;//当日

    protected $_t;//该月的总天数

    protected $_bw;//该月的第一天是周几

    protected $_ew;//该月的最后一天是周几

    protected $_data;//该月的集合

    protected $pagedata;//格式化后的该月的集合

    function __construct($y=null,$m=null)
    {
        $this->_y = $y ? : date('Y');
        $this->_m = $m ? : date('m');
        $this->_d = date('d');
        $this->_t = date('t',strtotime($this->_y.$this->_m.'01'));
        $this->_bw = date('w',strtotime($this->_y.$this->_m.'01'));
        $this->_ew = date('w',strtotime($this->_y.$this->_m.$this->_t));
    }

    public function page()
    {
        echo '<table style="background:#F00;">';
        $this->_seeHeader();
        $this->__getData();
        $this->_seeBriefBody();
        echo '</table>';
    }

    private function _seeHeader()
    {
        $arr = ['日','一','二','三','四','五','六'];

        echo '<tr><td colspan="7" align="center" style="height: 38px;">'.$this->_y.'年'.$this->_m.'月'.'</td></tr>';

        echo '<tr border="0" cellspacing="1" cellpadding="0" style="height: 30px;">';

        foreach($arr as $val)
        {
            echo '<th style="width: 40px;">'.$val.'</th>';
        }

        echo '</tr>';
    }

    protected function _seeBriefBody()
    {
        foreach($this->pagedata as $row)
        {
            echo '<tr>';
            foreach($row as $val)
            {
                echo '<td style="background:#FFF;width: 40px;" align="center">';
                if($val == -1)
                    echo '';
                else
                    echo $val;

                echo '</td>';
            }
            echo '</tr>';
        }
    }

    private function __getData()
    {
        $this->_data = range(1,$this->_t);
        //填充头部
        $this->_fillHead();
        //填充尾部
        $this->_fillFoot();
        $rows = count($this->_data) / 7;
        $count = count($this->_data);
        $result = [];
        for($i = 0;$i <= $rows; $i++)
        {
            for($j = 0; $j < $count; $j++ )
            {
                if(((($j+1) / 7) > $i) && ((($j+1) / 7) <= ($i+1) ))
                {
                    $result[$i][] = $this->_data[$j];
                }
            }
        }
        $this->pagedata = $result;
    }

    protected function _fillHead()
    {
        for($i = 0; $i < $this->_bw; $i++)
        {
            array_unshift($this->_data,'-1');
        }
    }

    protected function _fillFoot()
    {
        $fill_count = 6 - $this->_ew;
        for($i = 0;$i < $fill_count; $i++)
        {
            array_push($this->_data,'-1');
        }
    }

}


$object = new Calendar();
$object->page();
$object = new Calendar(2017,'01');
$object->page();
$object = new Calendar(2017,'11');
$object->page();
$object = new Calendar(2017,'02');
$object->page();
$object = new Calendar(2018,'02');
$object->page();
