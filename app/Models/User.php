<?php

namespace App\Models;

use App\Enums\TimezoneEnum;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, Auditable, FilamentUser
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, HasRoles, \OwenIt\Auditing\Auditable, SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'dial_code',
        'contact_no',
        'password',
        'status',
        'user_type',
        'auth_id',
        'auth_type',
        'store_id',
        'last_seen',
        'profile_photo_path',
        'is_quick_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'auth_id',
        'auth_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'is_quick_login' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Determine if the user can access the Filament panel.
     *
     * @param \Filament\Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Get the FCM tokens associated with the user.
     */
    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }

    public function employee_store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function employee_orders(): HasMany
    {
        return $this->hasMany(Order::class, 'employee_id');
    }

    public function employee_branches(): BelongsToMany
    {
        return $this->belongsToMany(StoreBranch::class, 'branch_employees', 'employee_id', 'store_branch_id')
            ->withPivot('role_id')->withTimestamps();
    }

    public function employee_roles()
    {
        return $this->belongsToMany(Role::class, 'branch_employees', 'employee_id', 'role_id')
            ->withPivot('store_branch_id')->withTimestamps();
    }

    public function owner_stores(): HasMany
    {
        return $this->hasMany(Store::class, 'owner_id');
    }

    public function customer_with_special_needs(): HasOne
    {
        return $this->hasOne(CustomerWithSpecialNeeds::class, 'customer_id');
    }

    public function customer_vehicles(): HasMany
    {
        return $this->hasMany(CustomerVehicle::class, 'customer_id');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function terms_conditions()
    {
        return $this->belongsToMany(TermsCondition::class, 'terms_conditions_approves_by_users', 'user_id', 'terms_condition_id')
            ->withPivot('approved')->withTimestamps();
    }

    public function privacy_policies()
    {
        return $this->belongsToMany(PrivacyPolicy::class, 'privacy_policy_approved_by_users', 'user_id', 'privacy_policy_id')
            ->withPivot('approved')->withTimestamps();
    }

    public function orderRatings(): HasMany
    {
        return $this->hasMany(OrderRating::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function app_notifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'coupons_for_users', 'user_id', 'coupon_id')->withPivot('usage_count')->withTimestamps();
    }

    public function coupon_redemption_log(): HasMany
    {
        return $this->hasMany(CouponRedemptionLog::class);
    }

    public function favorite_branches(): BelongsToMany
    {
        return $this->belongsToMany(StoreBranch::class, 'customer_favorite_branches', 'customer_id', 'store_branch_id')->withTimestamps();
    }

    public function bank_cards()
    {
        return $this->hasMany(BankCard::class, 'customer_id');
    }

    public function branch_visitors()
    {
        return $this->hasMany(BranchVisitor::class, 'user_id');
    }

    /**
     * Get the platform ratings for the user.
     */
    public function platform_ratings()
    {
        return $this->hasMany(PlatformRating::class);
    }

    /**
     * Determine if the user has verified their phone.
     *
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function getUserPhoneNumber()
    {
        return $this->dial_code . $this->contact_no;
    }

    public function is_working_now()
    {
        // Check if the employee has an open workday for today
        $openWorkDay = WorkDay::where('employee_id', $this->id)
            ->where('work_date', now()->toDateString())
            ->whereNull('end_time')
            ->first();

        return $openWorkDay !== null;
    }

    public function has_started_work_today()
    {
        return WorkDay::where('employee_id', $this->id)
            ->where('work_date', now()->toDateString())
            ->exists();
    }

    public function has_ended_work_today()
    {
        return WorkDay::where('employee_id', $this->id)
            ->where('work_date', now()->toDateString())
            ->whereNotNull('end_time') // Assuming 'end_time' is not null when the workday is ended
            ->exists();
    }

    public function get_current_work_day()
    {
        return WorkDay::where('employee_id', $this->id)
            ->where('work_date', now()->toDateString())
            ->first();
    }


    // ---------------------------------------------- working hours --------------------------------------------------- //
    public function get_total_hours_today()
    {
        return $this->get_total_hours_within_date_range(now()->startOfDay(), now()->endOfDay());
    }

    public function get_total_hours_last_week()
    {
        return $this->get_total_hours_within_date_range(now()->subWeek(), now());
    }

    public function get_total_hours_this_month()
    {
        return $this->get_total_hours_within_date_range(now()->startOfMonth(), now()->endOfMonth());
    }

    public function get_total_hours_last_three_months()
    {
        return $this->get_total_hours_within_date_range(now()->subMonths(3)->startOfMonth(), now()->endOfMonth());
    }

    public function get_total_hours_this_year()
    {
        return $this->get_total_hours_within_date_range(now()->startOfYear(), now()->endOfYear());
    }

    private function get_total_hours_within_date_range($startDate, $endDate)
    {
        // Get all workdays for the current day
        $workdays = WorkDay::where('employee_id', $this->id)
            ->whereBetween('work_date', [$startDate, $endDate])
            ->get();

        // Calculate total hours worked in one day
        $totalHoursOneDay = 0;

        foreach ($workdays as $workday) {
            if ($workday->start_time && $workday->end_time) {
                $startTime = Carbon::parse($workday->start_time);
                $endTime = Carbon::parse($workday->end_time);
                $totalHoursOneDay += $endTime->diffInHours($startTime);
            }
        }
        $totalSecondsOneDay = 0;

        foreach ($workdays as $workday) {
            if ($workday->start_time && $workday->end_time) {
                $startTime = Carbon::parse($workday->start_time);
                $endTime = Carbon::parse($workday->end_time);
                $totalSecondsOneDay += $endTime->diffInSeconds($startTime);
            }
        }

        // Convert total seconds to hours, minutes, and seconds
        $hours = floor($totalSecondsOneDay / 3600); // 1 hour = 3600 seconds
        $minutes = floor(($totalSecondsOneDay % 3600) / 60);
        $seconds = $totalSecondsOneDay % 60;

        // Format the hours, minutes, and seconds into "HH:MM:SS" format with leading zeros
        $formattedTime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

        // Reset the default timezone to its previous setting
        date_default_timezone_set(config('app.timezone'));

        return $formattedTime;
    }
    // ---------------------------------------------------------------------------------------------------------- //


    // ---------------------------------------------- orders count --------------------------------------------------- //
    public function get_total_orders_today()
    {
        return $this->get_total_orders_within_date_range(
            now(request('timezone'))->startOfDay(),
            now(request('timezone'))->endOfDay(),
        );
    }

    public function get_total_orders_last_week()
    {
        return $this->get_total_orders_within_date_range(
            now(request('timezone'))->subWeek(),
            now(request('timezone'))->endOfWeek(),
        );
    }

    public function get_total_orders_last_month()
    {
        return $this->get_total_orders_within_date_range(
            now(request('timezone'))->subMonth(),
            now(request('timezone'))->endOfMonth(),
        );
    }

    public function get_total_orders_this_month()
    {
        return $this->get_total_orders_within_date_range(
            now(request('timezone'))->startOfMonth(),
            now(request('timezone'))->endOfMonth(),
        );
    }

    public function get_total_orders_last_three_months()
    {
        return $this->get_total_orders_within_date_range(
            now(request('timezone'))->subMonths(3)->startOfMonth(),
            now(request('timezone'))->endOfMonth(),
        );
    }

    public function get_total_orders_this_year()
    {
        return $this->get_total_orders_within_date_range(
            now(request('timezone'))->startOfYear(),
            now(request('timezone'))->endOfYear(),
        );
    }

    private function get_total_orders_within_date_range($startDate, $endDate)
    {
        $timezoneDifHours = get_request_timezone_diff_hours();

        $count = DB::table('orders')
            ->where('employee_id', $this->id)
            ->whereBetween(DB::raw("CONVERT_TZ(order_date, '+00:00', '$timezoneDifHours')"), [$startDate, $endDate])
            ->count();

        return (string)max(0, $count);
    }
    // ---------------------------------------------------------------------------------------------------------- //


    // ---------------------------------------------- orders sales --------------------------------------------------- //
    public function get_total_cost_today()
    {
        return $this->get_total_cost_within_date_range(
            now(request('timezone'))->startOfDay(),
            now(request('timezone'))->endOfDay(),
        );
    }

    public function get_total_cost_last_week()
    {
        return $this->get_total_cost_within_date_range(now(request('timezone'))->subWeek(), now(request('timezone')));
    }

    public function get_total_cost_last_month()
    {
        return $this->get_total_cost_within_date_range(now(request('timezone'))->subMonth(), now(request('timezone')));
    }

    public function get_total_cost_this_month()
    {
        return $this->get_total_cost_within_date_range(
            now(request('timezone'))->startOfMonth(),
            now(request('timezone'))->endOfMonth(),
        );
    }

    public function get_total_cost_last_three_months()
    {
        return $this->get_total_cost_within_date_range(now(request('timezone'))->subMonths(3), now(request('timezone')));
    }

    public function get_total_cost_this_year()
    {
        return $this->get_total_cost_within_date_range(
            now(request('timezone'))->startOfYear(),
            now(request('timezone'))->endOfYear(),
        );
    }

    private function get_total_cost_within_date_range($startDate, $endDate)
    {
        $timezoneDifHours = data_get(TimezoneEnum::DifHours(), request('timezone', config('app.timezone')), '');

        $sum = DB::table('orders')
            ->where('employee_id', $this->id)
            ->whereBetween(DB::raw("CONVERT_TZ(order_date, '+00:00', '$timezoneDifHours')"), [$startDate, $endDate])
            ->sum('sub_total');

        return (string)max(0, $sum);
    }

    // ---------------------------------------------------------------------------------------------------------- //

    public function get_average_rating_employee()
    {
        $averageRating = DB::table('order_ratings')
            ->join('orders', 'order_ratings.order_id', '=', 'orders.id')
            ->where('orders.employee_id', $this->id)
            // ->where('order_ratings.rating', 5) // Assuming you want to filter by 5-star ratings
            ->avg('order_ratings.rating');

        return ceil($averageRating) ?? 0; // Round up to the nearest integer and return 0 if null
    }


    //----------------------------------------------------------------------------------------------------------//

    public function getNameUserAttribute()
    {
        return $this->name;
    }

//    public function getOrdersAttribute()
//    {
//        return $this->orders()->id;
//    }
}
