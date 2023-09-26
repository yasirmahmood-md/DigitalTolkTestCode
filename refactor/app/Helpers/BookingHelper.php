<?php

namespace DTApi\Helpers;


/**
 * Function to get all Potential jobs of user with his ID
 * @param $user_id
 * @return array
 */
function getPotentialJobIdsWithUserId($user_id): array
{
    $user_meta = UserMeta::where('user_id', $user_id)->first();
    $translator_type = $user_meta->translator_type;
    $job_type = 'unpaid';
    if ($translator_type == 'professional')
        $job_type = 'paid';   /*show all jobs for professionals.*/
    else if ($translator_type == 'rwstranslator')
        $job_type = 'rws';  /* for rwstranslator only show rws jobs. */
    else if ($translator_type == 'volunteer')
        $job_type = 'unpaid';  /* for volunteers only show unpaid jobs. */

    $languages = UserLanguages::where('user_id', '=', $user_id)->get();
    $userlanguage = collect($languages)->pluck('lang_id')->all();
    $gender = $user_meta->gender;
    $translator_level = $user_meta->translator_level;
    $job_ids = Job::getJobs($user_id, $job_type, 'pending', $userlanguage, $gender, $translator_level);
    foreach ($job_ids as $k => $v)     // checking translator town
    {
        $job = Job::find($v->id);
        $jobuserid = $job->user_id;
        $checktown = Job::checkTowns($jobuserid, $user_id);
        if (($job->customer_phone_type == 'no' || $job->customer_phone_type == '') && $job->customer_physical_type == 'yes' && $checktown == false) {
            unset($job_ids[$k]);
        }
    }
    $jobs = TeHelper::convertJobIdsInObjs($job_ids);
    return $jobs;
}


/**
 * @param Job $job
 * @return mixed
 */
function getPotentialTranslators(Job $job)
{

    $job_type = $job->job_type;

    if ($job_type == 'paid')
        $translator_type = 'professional';
    else if ($job_type == 'rws')
        $translator_type = 'rwstranslator';
    else if ($job_type == 'unpaid')
        $translator_type = 'volunteer';

    $joblanguage = $job->from_language_id;
    $gender = $job->gender;
    $translator_level = [];
    if (!empty($job->certified)) {
        if ($job->certified == 'yes' || $job->certified == 'both') {
            $translator_level[] = 'Certified';
            $translator_level[] = 'Certified with specialisation in law';
            $translator_level[] = 'Certified with specialisation in health care';
        } elseif ($job->certified == 'law' || $job->certified == 'n_law') {
            $translator_level[] = 'Certified with specialisation in law';
        } elseif ($job->certified == 'health' || $job->certified == 'n_health') {
            $translator_level[] = 'Certified with specialisation in health care';
        } else if ($job->certified == 'normal' || $job->certified == 'both') {
            $translator_level[] = 'Layman';
            $translator_level[] = 'Read Translation courses';
        } elseif ($job->certified == null) {
            $translator_level[] = 'Certified';
            $translator_level[] = 'Certified with specialisation in law';
            $translator_level[] = 'Certified with specialisation in health care';
            $translator_level[] = 'Layman';
            $translator_level[] = 'Read Translation courses';
        }
    }

    $blacklist = UsersBlacklist::where('user_id', $job->user_id)->get();
    $translatorsId = collect($blacklist)->pluck('translator_id')->all();
    $users = User::getPotentialUsers($translator_type, $joblanguage, $gender, $translator_level, $translatorsId);

//        foreach ($job_ids as $k => $v)     // checking translator town
//        {
//            $job = Job::find($v->id);
//            $jobuserid = $job->user_id;
//            $checktown = Job::checkTowns($jobuserid, $user_id);
//            if (($job->customer_phone_type == 'no' || $job->customer_phone_type == '') && $job->customer_physical_type == 'yes' && $checktown == false) {
//                unset($job_ids[$k]);
//            }
//        }
//        $jobs = TeHelper::convertJobIdsInObjs($job_ids);
    return $users;

}

/**
 * making user_tags string from users array for creating onesignal notifications
 * @param $users
 * @return string
 */
function getUserTagsStringFromArray($users)
{
    $user_tags = "[";
    $first = true;
    foreach ($users as $oneUser) {
        if ($first) {
            $first = false;
        } else {
            $user_tags .= ',{"operator": "OR"},';
        }
        $user_tags .= '{"key": "email", "relation": "=", "value": "' . strtolower($oneUser->email) . '"}';
    }
    $user_tags .= ']';
    return $user_tags;
}


function userLoginFailed(): array
{
    $throttles = Throttles::where('ignore', 0)->with('user')->paginate(15);

    return ['throttles' => $throttles];
}

function ignoreExpiring($id): array
{
    $job = Job::find($id);
    $job->ignore = 1;
    $job->save();
    return ['success', 'Changes saved'];
}

function ignoreExpired($id): array
{
    $job = Job::find($id);
    $job->ignore_expired = 1;
    $job->save();
    return ['success', 'Changes saved'];
}

function ignoreThrottle($id): array
{
    $throttle = Throttles::find($id);
    $throttle->ignore = 1;
    $throttle->save();
    return ['success', 'Changes saved'];
}

/**
 * Convert number of minutes to hour and minute variant
 * @param int $time
 * @param string $format
 * @return string
 */
function convertToHoursMins($time, $format = '%02dh %02dmin')
{
    if ($time < 60) {
        return $time . 'min';
    } else if ($time == 60) {
        return '1h';
    }

    $hours = floor($time / 60);
    $minutes = ($time % 60);

    return sprintf($format, $hours, $minutes);
}

/**
 * Function to delay the push
 * @param $user_id
 * @return bool
 */
function isNeedToDelayPush($user_id): bool
{
    $delay = false;

    $not_get_nighttime = TeHelper::getUsermeta($user_id, 'not_get_nighttime');

    if ($not_get_nighttime == 'yes') {
        $delay = true;
    }

    return $delay;
}

/**
 * Function to check if need to send the push
 * @param $user_id
 * @return bool
 */
function isNeedToSendPush($user_id): bool
{
    $send = true;
    $not_get_notification = TeHelper::getUsermeta($user_id, 'not_get_notification');
    if ($not_get_notification == 'yes') {
        $send = false;
    }

    return $send;
}