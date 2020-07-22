<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\DestroyPage;
use App\Http\Requests\Admin\Page\IndexPage;
use App\Http\Requests\Admin\Page\StorePage;
use App\Http\Requests\Admin\Page\UpdatePage;
use App\Models\Page;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexPage $request
     * @return Response|array
     */
    public function index(IndexPage $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Page::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'title', 'url', 'seo_description', 'seo_keywords', 'published_at', 'cover_image'],

            // set columns to searchIn
            ['id', 'title', 'url', 'content', 'seo_description', 'seo_keywords', 'cover_image']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.page.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Response
     */
    public function create()
    {
        $this->authorize('admin.page.create');

        return view('admin.page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePage $request
     * @return Response|array
     */
    public function store(StorePage $request)
    {
        // Sanitize input
        $sanitized = $request->validated();

        // Store the Page
        $page = Page::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/pages'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/pages');
    }

    /**
     * Display the specified resource.
     *
     * @param Page $page
     * @throws AuthorizationException
     * @return void
     */
    public function show(Page $page)
    {
        $this->authorize('admin.page.show', $page);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Page $page
     * @throws AuthorizationException
     * @return Response
     */
    public function edit(Page $page)
    {
        $this->authorize('admin.page.edit', $page);


        return view('admin.page.edit', [
            'page' => $page,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePage $request
     * @param Page $page
     * @return Response|array
     */
    public function update(UpdatePage $request, Page $page)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Page
        $page->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/pages'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
                'object' => $page
            ];
        }

        return redirect('admin/pages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPage $request
     * @param Page $page
     * @throws Exception
     * @return Response|bool
     */
    public function destroy(DestroyPage $request, Page $page)
    {
        $page->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param DestroyPage $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(DestroyPage $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Page::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
