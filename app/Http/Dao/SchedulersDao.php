<?php

/**
 * Created by PhpStorm.
 * User: t-mizuma
 * Date: 2017/10/27
 * Time: 20:48
 */

namespace App\Http\Dao;
use App\Http\Dao\Abs\BaseDao;

class SchedulersDao extends BaseDao {

	protected $id_name = 'id';

	protected $table_name = 'schedulers';

	/**
	 * 日付で絞る
	 * @param $target_date
	 * @return \Illuminate\Support\Collection
	 */
	public function findTargetSchedulers( $target_date ) {
		$sql = $this->table->select('schedulers.*','rooms.name as room_name')
			->join('rooms', 'rooms.id', '=', 'schedulers.room_id')
			->whereBetween('schedulers.start_time', [$target_date . ' 00:00:00', $target_date . ' 23:59:59'])
			->whereNull('rooms.deleted_at')
			->whereNull('schedulers.deleted_at');
		return $sql->orderBy('schedulers.start_time')->orderBy('rooms.name')->get();
	}

	/**
	 * 日付と会議室で絞る
	 * @param $target_date
	 * @return \Illuminate\Support\Collection
	 */
	public function findTargetDayAndRoomIdSchedulers( $target_date, $room_id ) {
		$sql = $this->table->select('schedulers.*','rooms.name as room_name')
			->join('rooms', 'rooms.id', '=', 'schedulers.room_id')
			->whereBetween('schedulers.start_time', [$target_date . ' 00:00:00', $target_date . ' 23:59:59'])
			->where('rooms.id', $room_id)
			->whereNull('rooms.deleted_at')
			->whereNull('schedulers.deleted_at');
		return $sql->orderBy('schedulers.start_time')->orderBy('rooms.name')->get();
	}

	/**
	 * 日付と会議室で絞る
	 * @param $target_date
	 * @param $room_id
	 * @return \Illuminate\Support\Collection
	 */
	public function findTargetSchedulersByTargetDateAndRoomId( $target_date, $room_id ) {
		$sql = $this->table->select('schedulers.*','rooms.name as room_name')
			->join('rooms', 'rooms.id', '=', 'schedulers.room_id')
			->whereBetween('schedulers.start_time', [$target_date . ' 00:00:00', $target_date . ' 23:59:59'])
			->whereNull('rooms.deleted_at')
			->whereNull('schedulers.deleted_at');
		if (!empty($room_id)) {
			$sql = $sql->where('schedulers.room_id', '=', $room_id);
		}
		return $sql->orderBy('schedulers.start_time')->get();
	}

	public function isStartTimeDuplicate($room_id, $start_time, $end_time, $id = null) {
		$sql = $this->table
			->join('rooms', 'rooms.id', '=', 'schedulers.room_id')
			->whereBetween('schedulers.start_time', [$start_time, $end_time])
			->whereNull('rooms.deleted_at')
			->whereNull('schedulers.deleted_at')
			->where('schedulers.room_id', '=', $room_id);
		if (!empty($id)) {
			$sql = $sql->where('schedulers.id', '!=', $id);
		}
		$ret = $sql->count() != 0;
		return $ret;
	}

	public function isEndTimeDuplicate($room_id, $start_time, $end_time, $id = null) {
		$sql = $this->table
			->join('rooms as r', 'r.id', '=', 'schedulers.room_id')
			->whereNotBetween('schedulers.end_time', [$start_time, $end_time])
			->whereNull('r.deleted_at')
			->whereNull('schedulers.deleted_at')
			->where('schedulers.room_id', '=', $room_id);
		if (!empty($id)) {
			$sql = $sql->where('schedulers.id', '!=', $id);
		}
		$ret = $sql->count() != 0;
		return $ret;
	}
}