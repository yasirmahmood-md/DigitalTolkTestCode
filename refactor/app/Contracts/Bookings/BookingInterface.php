<?php

namespace DTApi\Contracts\Bookings;

use Carbon\Carbon;
use DTApi\Events\JobWasCanceled;
use DTApi\Events\JobWasCreated;
use DTApi\Events\SessionEnded;
use DTApi\Helpers\DateTimeHelper;
use DTApi\Helpers\SendSMSHelper;
use DTApi\Mailers\AppMailer;
use DTApi\Mailers\MailerInterface;
use DTApi\Models\Job;
use DTApi\Models\Language;
use DTApi\Models\Translator;
use DTApi\Models\User;
use DTApi\Models\UserLanguages;
use DTApi\Models\UserMeta;
use DTApi\Models\UsersBlacklist;
use Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

interface BookingInterface
{

    /**
     * @param $user_id
     * @return array
     */
    public function getUsersJobs($user_id): array;

    /**
     * @param $user_id
     * @return array
     */
    public function getUsersJobsHistory($user_id, Request $request): array;

    /**
     * @param $user
     * @param $data
     * @return mixed
     */
    public function store($user, $data);

    /**
     * @param $data
     * @return mixed
     */
    public function storeJobEmail($data);

    /**
     * @param $job
     * @return array
     */
    public function jobToData($job): array;

    /**
     * @param array $post_data
     * @return void
     */
    public function jobEnd(array $post_data = array());


    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateJob($id, $data, $cuser);


    /**
     * @param $data
     * @param $user
     * @return array
     */
    public function acceptJob($data, $user): array;

    /**
     * @param $job_id
     * @param $cuser
     * @return array
     */
    public function acceptJobWithId($job_id, $cuser): array;

    /**
     * @param $data
     * @param $user
     * @return array
     */
    public function cancelJobAjax($data, $user): array;

    public function getPotentialJobs($cuser);


    /**
     * @param $post_data
     * @return array
     */
    public function endJob($post_data): array;

    /**
     * @param $post_data
     * @return array
     */
    public function customerNotCall($post_data): array;

    /**
     * @param Request $request
     * @param null $limit
     */
    public function getAll(Request $request, $limit = null);

    public function alerts(): array;


    public function bookingExpireNoAccepted(): array;

    public function reopen($request): array;
}
