<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    abort(404);
//    return redirect()->route('dashboard');
});

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]],function(){

    /*  For All Operation Of Auth And Restore Password Start */
    Route::group(['prefix'=>'dashboard','middleware'=>'AdminGuest:webadmin'],function(){
        Route::get('login','AdminAuthController@LoginForm')->name('ShowLoginForm');
        Route::post('login','AdminAuthController@Login')->name('DoLogin');

        Route::get('forget','AdminAuthController@ForgetPasswordForm')->name('ShowForgetPasswordForm');
        Route::post('forget','AdminAuthController@ForgetPassword')->name('DoForgetPassword');

        Route::get('reset/{token}','AdminAuthController@ResetPasswordForm')->name('ShowResetPasswordForm');
        Route::post('reset/{token}','AdminAuthController@ResetPassword')->name('DoResetPassword');
    });
    /*  For All Operation Of Auth And Restore Password End */


    Route::group(['prefix'=>'dashboard','middleware'=>'AdminAuth:webadmin'],function(){

        Route::get('/settings','SettingController@getSettings')->name('setting');
        Route::put('/settings','SettingController@updateSettings')->name('setting.update');



        /*  For All Operation Of File Manager And Browse Data In Server Start */
        Route::group(['prefix' => 'laravel-filemanager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });
        /*  For All Operation Of File Manager And Browse Data In Server End */


        /*  For All Operation Of DEAL With Imaged And All Media Download And Upload Start */
        Route::get('/uploaded_files/{model}/{name}/{id?}/{type?}','DashboardController@uploadedFiles')->name('UPLOADED.FILES');
//        Route::get('/download_files/{id}/{name}/{type?}','DashboardController@downloadFiles')->name('DOWNLOAD.FILES');
        Route::get('/download_local_exams/{course_id}/{file_name}/{new_file_name}','DashboardController@downloadLocalExams')->name('download.local.exams');
        Route::get('/download_cloud_exams/{file_path}/{new_file_name}','DashboardController@downloadCloudExams')->name('download.cloud.exams');
        Route::get('/download_local_attached_files/{course_id}/{file_name}/{new_file_name}','DashboardController@downloadLocalAttachedFiles')->name('download.local.attached.files');
        Route::get('/download_cloud_attached_files/{file_path}/{new_file_name}','DashboardController@downloadCloudAttachedFiles')->name('download.cloud.attached.files');
        /*  For All Operation Of DEAL With Imaged And All Media Download And Upload End */


        /*  For All Operation Of Logout And Dashboard Home After Login Start */
        Route::post('logout','AdminAuthController@Logout')->name('DoLogout');
        Route::get('/','DashboardController@index')->name('dashboard');
        /*  For All Operation Of Logout And Dashboard Home After Login End */


        /*  For All Operation Of Staff or Admin or Supervisor Start */
        Route::get('/admin/profile/{admin}','AdminController@account')->name('admin.show.profile');
        Route::put('/admin/profile/{admin}','AdminController@updateAccount')->name('admin.profile');
        Route::resource('/admin','AdminController');
        /*  For All Operation Of Staff or Admin or Supervisor End */


        /*  For All Operation Of University Start */
        Route::delete('/university/delete','UniversityController@multiDelete')->name('university.multi.delete');
        Route::put('/university/seo/{id}','UniversityController@seo')->name('university.seo');
        Route::resource('/university','UniversityController');
        /*  For All Operation Of University End */

        /*  For All Operation Of Departments Start */
        Route::put('/department/seo/{id}','DepartmentController@seo')->name('department.seo');
        Route::delete('/department/delete','DepartmentController@multiDelete')->name('department.multi.delete');
        Route::resource('/department','DepartmentController');
        /*  For All Operation Of Departments End */

        /*  For All Operation Of Instructor Start */
        Route::delete('/instructor/delete','InstructorController@multiDelete')->name('instructor.multi.delete');
        Route::resource('/instructor','InstructorController');
        /*  For All Operation Of Instructor End */

          /*  For All Operation Of Courses Start */
        Route::put('/course/seo/{id}','CourseController@seo')->name('course.seo');
        Route::delete('/course/delete','CourseController@multiDelete')->name('course.multi.delete');
        Route::get('/get_departments','CourseController@getDepartments')->name('university.departments.get');
        Route::post('/course/part/store','CourseController@addPart')->name('course.parts.store');
        Route::put('/course/part/update/{id}','CourseController@updatePart')->name('course.parts.update');
        Route::delete('/course/part/delete/{id}','CourseController@deletePart')->name('course.parts.delete');
        Route::get('/course/photos/{course_id}','CourseController@photos')->name('course.photos');
        Route::get('/course/reviews/{course_id}','CourseController@Reviews')->name('course.reviews');
        Route::delete('/delete-course-review/{review_id}','CourseController@destroyReview')->name('course.reviews.delete');
        Route::delete('/delete-course-review','CourseController@multiDeleteReviews')->name('course.reviews.multi.delete');

        Route::resource('/course','CourseController');
        /*  For All Operation Of Courses End */

        /*  For All Operation Of Exams Start */
        Route::delete('/exams/delete','ExamController@multiDelete')->name('exams.multi.delete');
        Route::get('/exams/create/{id}','ExamController@create')->name('exams.create');
        Route::resource('/exams','ExamController')->except(['index','create']);
        /*  For All Operation Of Exams End */

        /*  For All Operation Of Course Articles Start */
        Route::get('/show-course-articles/{course_id}','ArticleController@show')->name('course.article.show');
        Route::get('/create-course-article/{course_id}','ArticleController@create')->name('course.article.create');
        Route::post('/create-course-article','ArticleController@store')->name('course.article.store');
        Route::get('/edit-course-article/{course_id}','ArticleController@edit')->name('course.article.edit');
        Route::put('/edit-course-article/{course_id}','ArticleController@update')->name('course.article.update');
        Route::delete('/destroy-course-article/{course_id}','ArticleController@destroy')->name('course.article.destroy');
        Route::delete('/destroy-course-article','ArticleController@multiDelete')->name('course.article.multi.delete');
        /*  For All Operation Of Course Articles End */


        /*  For All Operation Of Lessons And Its Slides Or Attached Files Start */
        Route::delete('/lesson/delete','LessonController@multiDelete')->name('lesson.multi.delete');
        Route::get('/lesson/create/{id}','LessonController@create')->name('lesson.create');
//        Route::post('/lesson/file/store','LessonFileController@store')->name('lesson.file.store');
//        Route::delete('/lesson/file/destroy/{id}','LessonFileController@destroy')->name('lesson.file.destroy');
        Route::resource('/lesson','LessonController')->except(['index','create']);
        Route::delete('/lesson/files/destroy','LessonFileController@multiDelete')->name('lesson.file.multi.delete');
        Route::resource('/lesson/files','LessonFileController')->except(['index','create','edit']);
        Route::get('/lesson/reviews/{lesson_id}','LessonController@Reviews')->name('lesson.reviews');
        Route::delete('/delete-lesson-review/{review_id}','LessonController@destroyReview')->name('lesson.reviews.delete');
        Route::delete('/delete-lesson-review','LessonController@multiDeleteReviews')->name('lesson.reviews.multi.delete');
        /*  For All Operation Of Lessons And Its Slides Or Attached Files End */



        /*  For All Operation Of Category Of Blog Start */
        Route::delete('/category/delete','CategoryController@multiDelete')->name('category.multi.delete');
        Route::resource('/category','CategoryController')->except('show');
        /*  For All Operation Of Category Of Blog Start */


        /*  For All Operation Of Category Of Blog Start */
        Route::delete('/post/delete','PostController@multiDelete')->name('post.multi.delete');
        Route::resource('/post','PostController')->except('show');
        /*  For All Operation Of Category Of Blog Start */

        /*  For All Operation Of Tags Of Blog Start */
        Route::delete('/tag/delete','TagController@multiDelete')->name('tag.multi.delete');
        Route::resource('/tag','TagController')->except('show');
        /*  For All Operation Of Tags Of Blog Start */


        /*  For All Operation Of Feedback Start */
        Route::delete('/feedback/delete','FeedbackController@multiDelete')->name('feedback.multi.delete');
        Route::resource('/feedback','FeedbackController')->except('show');
        /*  For All Operation Of Feedback End */



        /*  For All Operation Of project Start */
        Route::delete('/project/delete','ProjectController@multiDelete')->name('project.multi.delete');
        Route::resource('/project','ProjectController');
        /*  For All Operation Of project End */




        /*  For All Operation Of Photos Or Any Media Files To Upload For Courses And Projects Start */
        Route::delete('/upload/delete','UploaderController@multiDelete')->name('upload.multi.delete');
        Route::delete('/upload/delete/{id}','UploaderController@destroy')->name('upload.destroy');
        Route::get('/upload/{model}/{id}','UploaderController@getUploadForm')->name('upload.form');
        Route::post('/upload/{model}/{id}','UploaderController@Upload')->name('upload');
        Route::put('/upload/{id}','UploaderController@updateStatus')->name('upload.update.status');
        /*  For All Operation Of Photos Or Any Media Files To Upload For Courses And Projects End */



        Route::delete('/orders/delete/{id}','OrdersController@destroy')->name('orders.destroy');
        Route::delete('/orders/delete','OrdersController@multiDelete')->name('orders.multi.delete');
        Route::get('/orders','OrdersController@index')->name('orders.index');
        Route::put('/orders/{id}','OrdersController@approve')->name('orders.approve');



        Route::get('/students','UserController@index')->name('students.index');
        Route::put('/students/{id}','UserController@update')->name('students.update');
        Route::delete('/students/{id}','UserController@destroy')->name('students.destroy');
        Route::delete('/students','UserController@multiDelete')->name('students.multi.delete');


























//
//        Route::get('/steps','DashboardController@steps');
//        Route::get('/permissions','DashboardController@permissions')->name('per');
//
////        For Datatable Features
//        Route::get('datatable/multidelete','PdfController@multidelete')->name('mul');
//        Route::get('datatable/normal','PdfController@normal')->name('normal');
//        Route::resource('datatable','PdfController');

    });
});
