<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login_with','usertype','first_name','last_name','gender', 'email', 'password','image_icon','mobile','contact_email','website','address','facebook_url','twitter_url','linkedin_url','dribbble_url','instagram_url','facebook_id','google_id','remember_token','subscription_id','stripe_customer_id','admin_notes','account_status','suspended_at','suspension_reason'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'suspended_at' => 'datetime',
    ];

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function isAdmin(): bool
    {
        return $this->usertype === 'admin' || $this->usertype === 'Admin';
    }

    public function isSuspended(): bool
    {
        return $this->account_status === 'suspended';
    }

    public function isBanned(): bool
    {
        return $this->account_status === 'banned';
    }


    public static function getUserInfo($id) 
    { 
        return User::find($id);
    }

    public static function getUserFullname($id) 
    { 
        $userinfo=User::find($id);

        return $userinfo->first_name.' '.$userinfo->last_name;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPassword($token));
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function hasFeature(string $feature): bool
    {
        $subscription = $this->activeSubscription;
        
        if (!$subscription) {
            $features = PackageFeature::getByTier('starter');
        } else {
            $features = $subscription->packageFeature();
        }
        
        if (!$features) {
            return false;
        }
        
        return $features->$feature ?? false;
    }

    public function deals()
    {
        return $this->hasMany(Deal::class, 'vendor_id');
    }

    public function vendorProfile()
    {
        return $this->hasOne(VendorProfile::class);
    }

    public function isVendor(): bool
    {
        return $this->usertype === 'Vendor';
    }

    /**
     * Get all course progress for this user
     */
    public function courseProgress()
    {
        return $this->hasMany(VendorCourseProgress::class);
    }

    /**
     * Get progress for a specific course
     */
    public function getCourseProgress(int $courseNumber): ?VendorCourseProgress
    {
        return $this->courseProgress()
            ->where('course_number', $courseNumber)
            ->first();
    }

    /**
     * Check if user has completed a specific course
     */
    public function hasCompletedCourse(int $courseNumber): bool
    {
        $progress = $this->getCourseProgress($courseNumber);
        return $progress && $progress->isCompleted();
    }

    /**
     * Check if user has completed all required training
     */
    public function hasCompletedAllTraining(): bool
    {
        if (!config('training.enabled') || !config('training.required')) {
            return true;
        }

        $courses = config('training.courses', []);
        
        foreach ($courses as $course) {
            if (isset($course['required']) && $course['required']) {
                if (!$this->hasCompletedCourse($course['number'])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the next course the user should complete
     */
    public function getNextCourse(): ?array
    {
        $courses = config('training.courses', []);
        
        foreach ($courses as $course) {
            // Check if course has prerequisite
            if (isset($course['prerequisite'])) {
                if (!$this->hasCompletedCourse($course['prerequisite'])) {
                    continue; // Skip if prerequisite not completed
                }
            }
            
            // If course not completed, return it
            if (!$this->hasCompletedCourse($course['number'])) {
                return $course;
            }
        }

        return null; // All courses completed
    }

    /**
     * Get total courses completed count
     */
    public function getCompletedCoursesCount(): int
    {
        return $this->courseProgress()
            ->where('passed', true)
            ->count();
    }
}


class CustomPassword extends ResetPassword
{
    public function toMail($notifiable)
    {   
        $url=url('password/reset/'.$this->token);

        return (new MailMessage)
            ->subject('Reset Password')
            ->from(getcong('site_email'), getcong('site_name'))
            /*->line('We are sending this email because we recieved a forgot password request.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required. Please contact us if you did not submit this request.');*/
            ->view('emails.password',['url'=>$url]);
    }
}