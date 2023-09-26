<?php

namespace DTApi\Contracts\Bookings;

use DTApi\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;

interface BaseInterface
{
    /**
     * @return array
     */
    public function validatorAttributeNames(): array;

    /**
     * @return Model
     */
    public function getModel(): Model;

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all();

    /**
     * @param integer $id
     * @return Model|null
     */
    public function find($id);

    public function with($array);

    /**
     * @param integer $id
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): Model;

    /**
     * @param string $slug
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findBySlug($slug): Model;

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): \Illuminate\Database\Eloquent\Builder;

    /**
     * @param array $attributes
     * @return Model
     */
    public function instance(array $attributes = []): Model;

    /**
     * @param int|null $perPage
     * @return mixed
     */
    public function paginate($perPage = null);

    public function where($key, $where);

    /**
     * @param array $data
     * @param null $rules
     * @param array $messages
     * @param array $customAttributes
     * @return \Illuminate\Validation\Validator
     */
    public function validator(array $data = [], $rules = null, array $messages = [], array $customAttributes = []): \Illuminate\Validation\Validator;

    /**
     * @param array $data
     * @param null $rules
     * @param array $messages
     * @param array $customAttributes
     * @return bool
     * @throws ValidationException
     */
    public function validate(array $data = [], $rules = null, array $messages = [], array $customAttributes = []): bool;

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data = []): Model;

    /**
     * @param integer $id
     * @param array $data
     * @return Model
     */
    public function update($id, array $data = []): Model;

    /**
     * @param integer $id
     * @return Model
     * @throws \Exception
     */
    public function delete($id): Model;


}
