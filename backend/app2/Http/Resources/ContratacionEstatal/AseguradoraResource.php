<?php

namespace App\Http\Resources\ContratacionEstatal;

use Illuminate\Http\Resources\Json\JsonResource;

class AseguradoraResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request) {
		return parent::toArray($request);
	}
}
