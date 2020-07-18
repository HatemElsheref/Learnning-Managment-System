<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/reset',function (){
\Illuminate\Support\Facades\Artisan::call('config:clear');
\Illuminate\Support\Facades\Artisan::call('cache:clear');
\Illuminate\Support\Facades\Artisan::call('view:clear');
return redirect()->back();
});
Route::get('/fresh',function (){
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed');
    return redirect()->back();
});


Route::fallback(function (){
//   return view('frontend.404');
    abort(404);
});

Auth::routes();

Route::group(['middleware'=>'blocked'],function (){
// now middleware needed for all visitors if comments not active
Route::group(['namespace'=>'Frontend','prefix'=>'blog'],function (){
    Route::get('/','BlogController@index')->name('blog');
    Route::get('/category/{slug}','BlogController@searchByCategory')->name('blog.category'); //slug->name
    Route::get('/tag/{slug}','BlogController@searchByTag')->name('blog.tag'); //slug->name
    Route::get('/search','BlogController@search')->name('blog.search');
    Route::get('/date/{date}','BlogController@searchByDate')->name('blog.date');
    Route::get('/post/{slug}','BlogController@post')->name('blog.post');
}) ;

// now middleware needed for all visitors
Route::group(['namespace'=>'Frontend','prefix'=>'projects'],function (){
    Route::get('/all','ProjectController@index')->name('projects');
    Route::get('/{id}','ProjectController@project')->name('project'); //id->project id
}) ;

// now middleware needed for all visitors
Route::group(['namespace'=>'Frontend','prefix'=>'university'],function (){
    Route::get('/{slug}','UniversityController@index')->name('university');
    Route::get('/departments/{slug}','UniversityController@university')->name('university.department');
}) ;

// now middleware needed for all visitors if comments not active
Route::group(['namespace'=>'Frontend','prefix'=>'articles'],function (){
    Route::get('/course/{slug}','ArticleController@index')->name('articles');   //show all articles of selected course by slug
    Route::get('/department/{slug}','ArticleController@department')->name('articles.department');   //show all articles of selected department by slug
    Route::get('/show/{slug}','ArticleController@article')->name('article');   //show selected article
    Route::get('/date/{date}','ArticleController@searchByDate')->name('article.date'); // show articles in this date (year)
    Route::get('/search','ArticleController@search')->name('article.search'); // show articles in this date (year)
}) ;



Route::group(['namespace'=>'Frontend','prefix'=>'courses'],function (){

    Route::get('/','CourseController@index')->name('courses');        //all courses
    Route::get('/show/{slug}','CourseController@course')->name('course.details');        //show one course with intro
    Route::post('/filter','CourseController@filter')->name('course.filter');        //show course based on filter
    Route::get('/search','CourseController@search')->name('course.search');        //show course based on any word
    Route::post('/rate-course','RateController@addCourseRate')->name('rate.course')->middleware(['web','auth:web']);
    Route::put('/rate-course/{id}','RateController@updateCourseRate')->name('rate.course.update')->middleware(['web','auth:web']);
    Route::get('/load-more-course-rates','CourseController@loadMoreCourseRates')->name('more.rate.course');

}) ;



//Route::get('/lessons/{slug}','Frontend\LessonController@index')->name('course.lessons');  //secured for paid and auth only
Route::get('/lessons/{slug}','Frontend\LessonController@local')->name('course.lessons');  //secured for paid and auth only
Route::get('/lesson/video/{course}/{lesson}','Frontend\LessonController@video')->name('course.video');  //secured for paid and auth only
Route::get('/getLocalVideoLesson/{course_id}/{lesson_id}','Frontend\LessonController@checkAuthorization')->name('course.video.response');  //secured for paid and auth only
Route::post('/rate-lesson','Frontend\RateController@addLessonRate')->name('rate.lesson')->middleware(['web','auth:web']);
Route::put('/rate-lesson/{id}','Frontend\RateController@updateLessonRate')->name('rate.lesson.update')->middleware(['web','auth:web']);
Route::get('/load-more-lesson-rates','Frontend\LessonController@loadMoreLessonRates')->name('more.rate.lesson');






Route::get('/uploaded_files/{model}/{name}/{id?}/{type?}','Frontend\FrontController@uploadedFiles')->name('storage');                                                           //anything
Route::get('/download_exam/{course_id}/{exam_id}','Frontend\FrontController@downloadExam')->name('storage.exam');                   //exams
Route::get('/download_files/{course_id}/{file_id}','Frontend\FrontController@downloadLessonFiles')->name('storageFiles.slides');       //slides
Route::post('/buy-course','Frontend\FrontController@buyCourse')->name('buy');
Route::post('/cancel-order/{order_id}','Frontend\FrontController@CancelOrder')->name('cancel.order');




Route::post('/getDepartments','Auth\RegisterController@getDepartments')->name('register.departments');

 Route::group(['middleware'=>'auth:web'],function (){
Route::post('/getDepartmentsInProfile','Frontend\FrontController@getDepartmentsInProfile')->name('profile.departments');
Route::get('/account','Frontend\FrontController@profile')->name('user.profile');
Route::put('/account','Frontend\FrontController@updateProfile')->name('user.profile.update');
Route::put('/account/password-reset','Frontend\FrontController@resetPassword')->name('user.profile.reset.password');
    });

Route::get('/','Frontend\FrontController@index')->name('index');
Route::get('/about','Frontend\PageController@about')->name('about');
Route::get('/contact','Frontend\PageController@contact')->name('contact');
Route::get('/services','Frontend\PageController@services')->name('services');







   });
















