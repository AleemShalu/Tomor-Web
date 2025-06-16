<?php

namespace App\Traits\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait UserIsAuthorizedTrait
{
    /**
     * check if the request user is authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|void
     */
    public function checkIfRequestHasAuthUser(Request $request)
    {
        if (!$request->user()) {
            return getJsonResponse(
                __('locale.api.errors.user_unauthenticated'),
                "AUTHENTICATION_ERROR",
                Response::HTTP_UNAUTHORIZED,
            );
        }
    }

    /**
     * check if the user has the right roles to use the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|void
     */
    public function checkIfUserHasRightRoles(Request $request, array $roles)
    {
        if (!$request->user()->hasAnyRole($roles)) {
            return getJsonResponse(
                __('locale.api.errors.user_does_not_have_necessary_permissions'),
                "FORBIDDEN_ERROR",
                Response::HTTP_FORBIDDEN,
            );
        }
    }

    /**
     * check if the user has the `owner` role and actually owns the store.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|void
     */
    public function checkIfUserOwnsStore(Request $request)
    {
        if ($request->user()->hasRole('owner') &&
            $request->user()->owner_stores()->where('id', request('store_id'))->doesntExist()
        ) {
            return getJsonResponse(
                __('locale.api.errors.user_is_forbidden_from_resource'),
                "FORBIDDEN_ERROR",
                Response::HTTP_FORBIDDEN,
            );
        }
    }

    /**
     * check if the user has the `worker_supervisor` or `worker` role and actually belongs to the store and the branch.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|void
     */
    public function checkIfUserBelongsToStoreAndBranch(Request $request)
    {
        if ($request->user()->hasAnyRole(['worker_supervisor', 'worker']) &&
            ($request->user()->store_id != request('store_id') ||
                $request->user()->employee_branches()->where('id', request('store_branch_id'))
                ->orWhere('id', request('branch_id'))->doesntExist())
        ) {
            return getJsonResponse(
                __('locale.api.errors.user_is_forbidden_from_resource'),
                "FORBIDDEN_ERROR",
                Response::HTTP_FORBIDDEN,
            );
        }
    }

    /**
     * check if the user has the `worker_supervisor` or `worker` role and actually belongs to the branch.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|void
     */
    public function checkIfUserBelongsToBranch(Request $request)
    {
        if ( $request->user()->hasAnyRole(['worker_supervisor', 'worker']) &&
            $request->user()->employee_branches()->where('id', request('store_branch_id'))
                ->orWhere('id', request('branch_id'))->doesntExist()
        ) {
            return getJsonResponse(
                __('locale.api.errors.user_is_forbidden_from_resource'),
                "FORBIDDEN_ERROR",
                Response::HTTP_FORBIDDEN,
            );
        }
    }

    public function checkIfUserIsOwnerOrSupervisor(Request $request)
    {
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        $error = $this->checkIfUserHasRightRoles($request, ['owner', 'worker_supervisor']);
        if ($error) return $error;

        $error = $this->checkIfUserOwnsStore($request);
        if ($error) return $error;

        $error = $this->checkIfUserBelongsToStoreAndBranch($request);
        if ($error) return $error;
    }

    public function checkIfUserIsOwnerAndBelongToStore(Request $request) {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['owner']);
        if ($error) return $error;

        // check if user is authorized to use the resource
        $error = $this->checkIfUserOwnsStore($request);
        if ($error) return $error;
    }
}
