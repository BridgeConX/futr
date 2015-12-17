<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $loadedEagerLoads = [];

    /**
     * JobsController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Collection $collection
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function collection(Collection $collection)
    {
        $this->parseEagerLoads($collection);

        return $this->response($collection);
    }

    /**
     * @param Model $model
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function model(Model $model)
    {
        $this->parseEagerLoads($model);

        return $this->response($model);
    }

    /**
     * @param $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function response($data)
    {
        return response()
          ->json([
            'eager' => [
              'allowed'   => $this->getAllowedEagerLoads(),
              'requested' => $this->getRequestedEagerLoads(),
              'loaded'    => $this->loadedEagerLoads,
            ],
            'data'  => $data,
          ]);
    }

    /**
     * @param Collection|Model $collection
     */
    protected function parseEagerLoads($collection)
    {
        $relations = array_intersect($this->getRequestedEagerLoads(), $this->getAllowedEagerLoads());
        $collection->load($relations);

        $this->loadedEagerLoads = array_merge($this->loadedEagerLoads, $relations);
    }

    /**
     * @return array
     */
    protected function getRequestedEagerLoads()
    {
        if (! $this->request->has('with')) {
            return [];
        }

        return explode(',', $this->request->get('with', ''));
    }

    /**
     * @return array
     */
    protected function getAllowedEagerLoads()
    {
        return $this->allowedEagerLoads ?: [];
    }
}
