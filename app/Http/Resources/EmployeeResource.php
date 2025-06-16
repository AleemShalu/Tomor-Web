<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'dial_code' => $this->dial_code,
            'contact_no' => $this->contact_no,
            'status' => $this->status,
            'store_id' => $this->store_id,

            // ------------------------------ working hours -------------------------------- //
            'total_hours_one_day' => $this->get_total_hours_today() ?? 0,
            'total_hours_week_one' => $this->get_total_hours_last_week() ?? 0,
            'total_hours_one_month' => $this->get_total_hours_this_month() ?? 0,
            'total_hours_three_months' => $this->get_total_hours_last_three_months() ?? 0,
            // ----------------------------------------------------------------------------- //

            // ------------------------------ order count -------------------------------- //
            'total_orders_one_day' => $this->get_total_orders_today() ?? 0,
            'total_orders_one_week' => $this->get_total_orders_last_week() ?? 0,
            'total_orders_one_month' => $this->get_total_orders_last_month() ?? 0,
            'total_orders_three_months' => $this->get_total_orders_last_three_months() ?? 0,
            // ----------------------------------------------------------------------------- //

            // ------------------------------ order sales -------------------------------- //
            'total_cost_one_day' => $this->get_total_cost_today() ?? 0,
            'total_cost_one_week' => $this->get_total_cost_last_week() ?? 0,
            'total_cost_one_month' => $this->get_total_cost_last_month() ?? 0,
            'total_cost_three_months' => $this->get_total_cost_last_three_months() ?? 0,
            // ----------------------------------------------------------------------------- //

            'average_rating' => $this->get_average_rating_employee() ?? 0,

            'profile_photo_url' => $this->profile_photo_url,
            'employee_store' => $this->whenLoaded('employee_store'),
            'employee_branches' => $this->whenLoaded('employee_branches'),
            // 'employee_roles' => $this->employee_roles,
            'roles' => $this->whenLoaded('roles'),
        ];

        // $timezone = request('timezone');
        // $configStartDate = convertDateToTimezone(now(request('timezone'))->startOfDay(), $timezone);
        // $configEndDate = convertDateToTimezone(now(request('timezone')), $timezone);
    }
}
