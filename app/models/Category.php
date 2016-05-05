<?php
class Category extends Eloquent {
	protected $table = 'category';
	public function majors() {
		return $this->hasMany('Major', 'category');
	}
}