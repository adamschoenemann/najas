<?php
namespace API;
class Images extends API {

	const TABLE_NAME = "images";

	private function process_model(&$model)
	{
		// $f3 = $this->f3;
		// $model->path = $f3->get("BASE") . "/". $f3->get("UPLOADS") . $model->path;
	}

	public function show($f3, $params)
	{
		$model = new \Models\Image($this->db);
		$models = $model->find();
		$result = array();
		foreach ($models as $m)
		{
			$this->process_model($m);
			$result[] = $m->cast();
		}
		echo \Utils::json_encode($result);
	}

	public function retrieve($f3, $params)
	{
		$model = new \Models\Image($this->db);
		$model = parent::_view($f3, $params, $model);
		
		$this->process_model($model);
		if(is_object($model))
		{
			echo \Utils::json_encode($model->cast());
		}
	}

	// public function upload($f3, $params)
	// {
	// 	try
	// 	{
	// 		$files = $f3->get("FILES");
	// 	}
	// 	catch(\Exception $e)
	// 	{
	// 		$this->handle_exception($e);
	// 	}
	// }

	public function create($f3, $params)
	{
		try {
			$this->db->begin();
			$post = $f3->get("POST");
			$files = $f3->get("FILES");
			
			if($this->is_image($files["image"]["type"]) === false)
			{
				throw new \InvalidArgumentException(
					"Image type " . $files["image"]["type"] . " is not supported");
			}

			$image = new \Models\Image($this->db);
			$image->copyFrom("POST");

			$filename = $this->upload_file($files, $f3);

			$image->path = $filename;
			$image->save();
			$this->db->commit();

			$json = $image->cast();
			echo \Utils::json_encode($json, false);

		}
		catch (\Exception $e)
		{
			$this->handle_exception($e);
		}
	}

	public function delete($f3, $params)
	{

	}

	/**
	 * WIP - first get file upload to work once again
	 * @param  [type]  $filename [description]
	 * @param  integer $width    [description]
	 * @param  integer $height   [description]
	 * @return [type]            [description]
	 */
	protected function generate_thumbs($filename, $width = 128, $height = 96)
	{
		$img = new Image("", false, $filename);
		$img->resize($width, $height);
		$info = pathinfo($filename);
		$new_name = $info["dirname"] . $info["filename"] . "{$width}x{$height}" . $info["extension"];
	}

	protected function is_image($filetype)
	{
		$img_types = array(
			"image/jpeg",
			"image/gif",
			"image/png",
			"image/bmp"
		);

		return in_array($filetype, $img_types);
	}

	protected function get_put()
	{
		return json_decode($this->f3->get("BODY"));
	}

	protected function upload_file($files, $f3)
	{
		$filename = \Utils::upload_file($files["image"], $f3);
		// remove /uploads from filename
		$filename = str_replace($f3->get("UPLOADS"), "", $filename);
		return $filename;
	}

	// TODO: Needs work and testing
	public function replace($f3, $params)
	{
		try 
		{
			$put = $this->get_put();
			$f3->set("put", $put);
			$model = new \Models\Image($this->db);
			$model->load(array("id=?", $put->id));

			if($put->title)
				$model->title = $put->title;

			if($put->description)
				$model->description = $put->description;

			if($put->path && $put->path != $model->path)
			{
				if(($files = $f3->get("FILES")) != NULL)
				{
					// TODO: also remove old image
					$filename = $this->upload_file($files, $f3);
				}
				$model->path = $put->path;
			}

			$model->save();
			echo \Utils::json_encode($model->cast());
		}
		catch(\Exception $e)
		{
			$this->handle_exception($e);
		}
		// print_r($put);
		// echo \Utils::json_encode($put) . "\n";
		// $model = parent::_update($f3, $params, new \Models\Image($this->db));
		// if(is_object($model))
		// {
		// }
	}

}