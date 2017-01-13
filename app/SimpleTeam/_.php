<?php

namespace App\SimpleTeam;

class _
{
	public static function each($items, $callback)
	{
		foreach ($items as $item) {
			$callback($item);
		}

		return $items;
	}

	public static function map($items, $callback)
	{
		return array_map($callback, $items);
	}

	public static function pluck($items, $property)
	{
		$item_props = _::map($items, function($item) use($property) {
			return $item->$property;
		});

		return $item_props;
	}

	public static function findWhere($array, $matching)
	{
		if (!is_array($array)) {
			return $array;
		}

		foreach ($array as $item) {
			$is_match = true;
			foreach ($matching as $key => $value) {
				if (is_object($item)) {
					if (!isset($item->$key)) {
						$is_match = false;
						break;
					}
				} else {
					if (!isset($item[$key])) {
						$is_match = false;
						break;
					}
				}

				if (is_object($item)) {
					if ($item->$key != $value) {
						$is_match = false;
						break;
					}
				} else {
					if ($item[$key] != $value) {
						$is_match = false;
						break;
					}
				}
			}

			if ($is_match) {
				return $item;
			}
		}

		return false;
	}
}
