<?php


namespace App\Http\Controllers\Dashboard;


trait NotificationTrait
{
    // language true to switch on translation file
    public function Notify($message,$title,$type,$language=true){

        if ($language===false){
            session()->flash('Notification_Direction',app()->getLocale());
            session()->flash('Notification_Type',$type);
            session()->flash('Notification_Msg',$message);
            session()->flash('Notification_Title',$title);
        } else{
            session()->flash('Notification_Direction',app()->getLocale());
            session()->flash('Notification_Type',$type);
            session()->flash('Notification_Msg',__('dashboard.'.$message));
            session()->flash('Notification_Title',__('dashboard.'.$title));
        }

    }
}


/**
 * How to use Notifications (Alerts) In System
 * Template Contain Built in Alerts In Sleek with js i developed it to connect to Laravel :
 * self::Notify('welcome elsheref','welcome','success'); use this command in any controller you need
 *  package i used it  http=> realrashid.github.io/sweet-alert/demo
 * has more than way to use
 * alert()->image('Image Title!','Image Description','Image URL','Image Width','Image Height');
 * alert()->error('ErrorAlert','Lorem ipsum dolor sit amet.');
 * alert()->question('QuestionAlert','Lorem ipsum dolor sit amet.');
 * toast('Success Toast','success','top-end'); // i updated this function in vendor Toaster class in Toaster.php file to take position
*/
