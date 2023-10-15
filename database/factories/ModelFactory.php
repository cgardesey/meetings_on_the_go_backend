<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Brackets\AdminAuth\Models\AdminUser::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt($faker->password),
        'remember_token' => null,
        'activated' => true,
        'forbidden' => $faker->boolean(),
        'language' => 'en',
        'deleted_at' => null,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
    ];
});/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Course::class, static function (Faker\Generator $faker) {
    return [
        'courseid' => $faker->sentence,
        'imageurl' => $faker->sentence,
        'coursecode' => $faker->sentence,
        'coursepath' => $faker->sentence,
        'description' => $faker->sentence,
        'about' => $faker->sentence,
        'active' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, static function (Faker\Generator $faker) {
    return [
        'userid' => $faker->sentence,
        'phonenumber' => $faker->sentence,
        'email' => $faker->email,
        'email_verified_at' => $faker->dateTime,
        'confirmation_token' => $faker->sentence,
        'password' => bcrypt($faker->password),
        'api_token' => $faker->sentence,
        'role' => $faker->sentence,
        'email_verified' => $faker->randomNumber(5),
        'active' => $faker->boolean(),
        'connected' => $faker->boolean(),
        'otp' => $faker->sentence,
        'apphash' => $faker->sentence,
        'osversion' => $faker->sentence,
        'sdkversion' => $faker->sentence,
        'device' => $faker->sentence,
        'devicemodel' => $faker->sentence,
        'deviceproduct' => $faker->sentence,
        'manufacturer' => $faker->sentence,
        'androidid' => $faker->sentence,
        'versionrelease' => $faker->sentence,
        'deviceheight' => $faker->sentence,
        'devicewidth' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\InstructorCourse::class, static function (Faker\Generator $faker) {
    return [
        'instructorcourseid' => $faker->sentence,
        'instructorid' => $faker->sentence,
        'courseid' => $faker->sentence,
        'institutionid' => $faker->sentence,
        'currency' => $faker->sentence,
        'price' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Instructor::class, static function (Faker\Generator $faker) {
    return [
        'userid' => $faker->sentence,
        'profilepicurl' => $faker->sentence,
        'title' => $faker->sentence,
        'firstname' => $faker->sentence,
        'lastname' => $faker->sentence,
        'othername' => $faker->sentence,
        'gender' => $faker->sentence,
        'dob' => $faker->sentence,
        'homeaddress' => $faker->sentence,
        'momo_number' => $faker->sentence,
        'maritalstatus' => $faker->sentence,
        'primarycontact' => $faker->sentence,
        'auxiliarycontact' => $faker->sentence,
        'edubackground' => $faker->sentence,
        'about' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Student::class, static function (Faker\Generator $faker) {
    return [
        'userid' => $faker->sentence,
        'profilepicurl' => $faker->sentence,
        'title' => $faker->sentence,
        'firstname' => $faker->sentence,
        'lastname' => $faker->sentence,
        'othername' => $faker->sentence,
        'gender' => $faker->sentence,
        'dob' => $faker->sentence,
        'homeaddress' => $faker->sentence,
        'maritalstatus' => $faker->sentence,
        'primarycontact' => $faker->sentence,
        'auxiliarycontact' => $faker->sentence,
        'highestedulevel' => $faker->sentence,
        'highesteduinstitutionname' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Assignment::class, static function (Faker\Generator $faker) {
    return [
        'assignmentid' => $faker->sentence,
        'title' => $faker->sentence,
        'url' => $faker->sentence,
        'instructorcourseid' => $faker->sentence,
        'submitdate' => $faker->date(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Banner::class, static function (Faker\Generator $faker) {
    return [
        'bannerid' => $faker->sentence,
        'url' => $faker->sentence,
        'criteria' => $faker->sentence,
        'cost' => $faker->randomNumber(5),
        'expirydate' => $faker->date(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Enrolment::class, static function (Faker\Generator $faker) {
    return [
        'enrolmentid' => $faker->sentence,
        'studentid' => $faker->sentence,
        'instructorcourseid' => $faker->sentence,
        'approved' => $faker->boolean(),
        'enrolled' => $faker->boolean(),
        'percentagecompleted' => $faker->randomNumber(5),
        'expirydate' => $faker->date(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Timetable::class, static function (Faker\Generator $faker) {
    return [
        'timetableid' => $faker->sentence,
        'dow' => $faker->sentence,
        'period_id' => $faker->sentence,
        'instructorcourseid' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Payment::class, static function (Faker\Generator $faker) {
    return [
        'paymentid' => $faker->sentence,
        'mobileno' => $faker->sentence,
        'chargeamount' => $faker->randomNumber(5),
        'description' => $faker->sentence,
        'message' => $faker->sentence,
        'responsecode' => $faker->sentence,
        'responsemessage' => $faker->sentence,
        'expirydate' => $faker->date(),
        'payerid' => $faker->sentence,
        'enrolmentid' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Period::class, static function (Faker\Generator $faker) {
    return [
        'starttime' => $faker->time(),
        'endtime' => $faker->time(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Institution::class, static function (Faker\Generator $faker) {
    return [
        'institutionid' => $faker->sentence,
        'name' => $faker->firstName,
        'level' => $faker->sentence,
        'address' => $faker->sentence,
        'location' => $faker->sentence,
        'contact' => $faker->sentence,
        'website' => $faker->sentence,
        'logourl' => $faker->sentence,
        'dateregistered' => $faker->sentence,
        'userid' => $faker->sentence,
        'active' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Audio::class, static function (Faker\Generator $faker) {
    return [
        'audioid' => $faker->sentence,
        'sessionid' => $faker->sentence,
        'title' => $faker->sentence,
        'url' => $faker->sentence,
        'instructorcourseid' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\ClassSession::class, static function (Faker\Generator $faker) {
    return [
        'classsessionid' => $faker->sentence,
        'sessionid' => $faker->sentence,
        'instructorcourseid' => $faker->sentence,
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'docurl' => $faker->sentence,
        'dialcode' => $faker->sentence,
        'roomid' => $faker->sentence,
        'islive' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\EnrolmentRequest::class, static function (Faker\Generator $faker) {
    return [
        'enrolmentrequestid' => $faker->sentence,
        'studentid' => $faker->sentence,
        'instructorcourseid' => $faker->sentence,
        'approved' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\InstructorCoursePeriod::class, static function (Faker\Generator $faker) {
    return [
        'instructorcourseperiodid' => $faker->sentence,
        'instructorcourseid' => $faker->sentence,
        'periodid' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\InstructorCourseRating::class, static function (Faker\Generator $faker) {
    return [
        'instructorcourseratingid' => $faker->sentence,
        'studentid' => $faker->sentence,
        'instructorcourseid' => $faker->sentence,
        'onestar' => $faker->boolean(),
        'twostar' => $faker->boolean(),
        'threestar' => $faker->boolean(),
        'fourstar' => $faker->boolean(),
        'fivestar' => $faker->boolean(),
        'review' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\InstructorInstitutionsAttended::class, static function (Faker\Generator $faker) {
    return [
        'institutionattendedid' => $faker->sentence,
        'institutionname' => $faker->sentence,
        'instructorid' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\InstructorPreferredTeachingTime::class, static function (Faker\Generator $faker) {
    return [
        'teachingtimeid' => $faker->sentence,
        'dow' => $faker->sentence,
        'starttime' => $faker->time(),
        'endtime' => $faker->time(),
        'instructorid' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Question::class, static function (Faker\Generator $faker) {
    return [
        'questionid' => $faker->sentence,
        'quizid' => $faker->sentence,
        'url' => $faker->sentence,
        'question' => $faker->text(),
        'correctans' => $faker->sentence,
        'optiona' => $faker->sentence,
        'optionb' => $faker->sentence,
        'optionc' => $faker->sentence,
        'optiond' => $faker->sentence,
        'optione' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Quiz::class, static function (Faker\Generator $faker) {
    return [
        'quizid' => $faker->sentence,
        'instructorcourseid' => $faker->sentence,
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'starttime' => $faker->time(),
        'endtime' => $faker->time(),
        'date' => $faker->date(),
        'url' => $faker->sentence,
        'question' => $faker->sentence,
        'active' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Attendance::class, static function (Faker\Generator $faker) {
    return [
        'attendanceid' => $faker->sentence,
        'audioid' => $faker->sentence,
        'duration' => $faker->randomNumber(5),
        'studentid' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\ChatSession::class, static function (Faker\Generator $faker) {
    return [
        'chatsessionid' => $faker->sentence,
        'instructorcourseid' => $faker->sentence,
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'docurl' => $faker->sentence,
        'islive' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
