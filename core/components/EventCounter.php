<?php

namespace core\components;

use yii\db\Query;

class EventCounter {
	private $_data;
	private $_dataTotal;
	private $_dataWeekTotal;
	private $_dateFrom = null;
	private $_dateTo = null;

	public function setRange($dateFrom, $dateTo)
	{
		$this->_dateFrom = $dateFrom;
		$this->_dateTo = $dateTo;
	}
	
	public static function track($event)
	{
		$date = date('Y-m-d');
		$command = \Yii::$app->db->createCommand("SELECT id FROM event_counter WHERE `date`='$date' AND event = :event");
		$command->bindValue(':event', $event);
		$exist = $command->queryOne();

		if (empty($exist)) {
			$command = \Yii::$app->db->createCommand("INSERT INTO event_counter (`date`, `event`, `cnt`) VALUES('$date', :event, 1)");
		} else {
			$command = \Yii::$app->db->createCommand("UPDATE event_counter SET cnt = cnt + 1 WHERE date = '$date' AND event = :event");
		}
		$command->bindValue(':event', $event);
		$command->execute();
	}

	protected function _prefetch(){
		if(!$this->_data){
			$this->_data = \Yii::$app->db
				->createCommand("SELECT * FROM event_counter")
				->queryAll();
		}
	}

	protected function _prefetchTotal(){
		if(!$this->_dataTotal){
			$q = new Query();
			$q->select('event, SUM(cnt) as total_cnt')->from('event_counter')->groupBy('event');

			if($this->_dateFrom){
				$q->andWhere("date >= :dt_from", ['dt_from' => $this->_dateFrom]);
			}

			if($this->_dateTo){
				$q->andWhere("date <= :dt_to", ['dt_to' => $this->_dateTo]);
			}

			$this->_dataTotal = $q->all();
		}
	}

	public function get($date, $event){
		$this->_prefetch();
		foreach($this->_data as $item){
			if($item['date'] == $date && $item['event'] == $event){
				return $item['cnt'];
			}
		}
		return 0;
	}

	public function getTotal($event = null, $prefix = '')
	{
		$this->_prefetchTotal();
		if($event){
			$event = $prefix . $event;
			foreach($this->_dataTotal as $item){
				if($item['event'] == $event){
					return $item['total_cnt'];
				}
			}
			return 0;
		} else {
			return $this->_dataTotal;
		}
	}

	public function getWeekTotal($event)
	{
		if(!$this->_dataWeekTotal){
			$weekAgo = date('Y-m-d', time() - 3600*24*7);
			$this->_dataWeekTotal = \Yii::$app->db
				->createCommand("SELECT event, SUM(cnt) as total_cnt FROM event_counter WHERE date > '$weekAgo' GROUP BY event")
				->queryAll();
		}
		foreach($this->_dataWeekTotal as $item){
			if($item['event'] == $event){
				return $item['total_cnt'];
			}
		}
		return 0;
	}

	public function getDates(){
		return \Yii::$app->db->createCommand("SELECT DISTINCT date FROM event_counter ORDER BY date")->queryColumn();
	}

	public function getNames()
	{
		return \Yii::$app->db->createCommand("SELECT `key`, `name`, `group_id` FROM event_name WHERE `show` = 1 ORDER BY ord")->queryAll();
	}

	public function getGroups()
	{
		return \Yii::$app->db->createCommand("SELECT `id`, `name` FROM event_group ORDER BY id")->queryAll();
	}
} 