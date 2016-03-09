<?php namespace SystemCore\DataMapper;
 
/**
 * RepositoryInterface provides the standard functions to be expected of ANY 
 * repository.
 *
 * @package SystemCore\DataMapper\RepositoryInterface
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
**/
interface RepositoryInterface {
	
	public function getInstance(array $attributes = array());

	public function with(array $with = array());

	public function create(array $attributes);
 
	public function all($columns = array('*'));
 
	public function find($id, $columns = array('*'));
 
	public function destroy($ids);
 
}