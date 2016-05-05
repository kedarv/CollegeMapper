<?php
class Major extends Eloquent {
	protected $table = 'majors';
	public function category() {
        return $this->belongsTo('Category');
    }
}