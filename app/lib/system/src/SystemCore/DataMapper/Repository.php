<?php namespace SystemCore\DataMapper;
 
/**
 * The Abstract Repository provides default implementations of the methods defined
 * in the base repository interface. These simply delegate static function calls 
 * to the right eloquent model based on the $modelClassName.
 *
 * @package SystemCore\DataMapper\Repository
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
**/

use Illuminate\Container\Container;

abstract class Repository implements RepositoryInterface {
	
	protected $modelClassName;

	protected $model;

    public function __construct()
    {
    	$app = new Container;
        $this->model = $app->make($this->modelClassName);
    }
 	
 	/**
	 * Make a new instance of the entity to query on
	 *
	 * @param array $attributes = array()
	 */
 	public function getInstance(array $attributes = array())
 	{
 		return $this->model->newInstance( $attributes );
 	}

 	/**
	 * Eager Load, Model Relationship
	 *
	 * @param array $with = array()
	 */
	public function with(array $with = array())
	{
	  return $this->model->with($with);
	}

	/**
	 * Call Create Instance and prepare for inserting in our Model.
	 *
	 * @param array $attributes
	 */
	public function create(array $attributes)
	{
		return $this->model->create($attributes);
	}

	/**
	 * Make a new instance of the entity to query on
	 *
	 * @param array $columns
	 */
	public function all($columns = array('*'))
	{
		return $this->model->all($columns);
	}
 	
 	/**
	 * Find specific data by $id.
	 *
	 * @param $id, $columns = array('*')
	 */
	public function find($id, $columns = array('*'))
	{
		return $this->model->find($id, $columns);
	}
	
	/**
	 * Make an update for specific model by its attributes
	 *
	 * @param array $attributes = array(
	 */
	public function update(array $attributes = array())
	{
		return $this->model->update($attributes);
	}


	/**
	 * Destroy Current Model by ids..
	 *
	 * @param array $ids
	 */	
	public function destroy($ids)
	{
		return $this->model->destroy($ids);
	}
}