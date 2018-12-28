<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Error or success messages
| -------------------------------------------------------------------
*/

// API Message
$lang['PROJECT_NAME'] = 'Budfie';
$lang['INVALID_REQUEST']= "Invalid Request";
$lang['PARAM_ERROR']= "Parameters Error";
$lang['PARAM_MISSING']= "Required Parameters are Missing";
$lang['ACCESSTOKEN_MISMATCH']= "Access Token Mismatch";
$lang['METHOD_MISMATCH']= "Method Not Found";
$lang['PARAMS_ERROR']= "Parameters Missing";
$lang['APIRESULT_SUCCESS']= "SUCCESS";
$lang['APIRESULT_FAILURE']= "FAILURE";
$lang['INVALID_ACCESS']= "Invalid Access";
$lang['NO_DATA_FOUND']= "Record Not Found";
$lang['NUMBER_NOT_FOUND']= "Number Not Found";
$lang['BLOCKED_USER']= "This user is blocked by Admin";
$lang['DELETED_USER']= "This user is deleted by Admin";
$lang['NOT_FOUND']= "Record not found";
$lang['INVALID_DATA']= "Invalid Credentials";
$lang['LOGIN_SUCCESS']= "Login Success";
$lang['INVALID_OTP']= "OTP is not correct";
$lang['OTP_VERIFIED']= "OTP has been verified successfully";
$lang['OTP_SUCCESS']= "OTP has been sent successfully";
$lang['OTP_NOT_VERIFIED']= "OTP is not verified";
$lang['PROFILE_SUCCESS']= "User's details has been fetched successfully";
$lang['UPDATE_SUCCESS']= "Profile has been updated successfully";
$lang['INTEREST_SUCCESS']= "Interest list has been fetched successfully";
$lang['INTEREST_ADD']= "Interests has been added successfully";
$lang['CONTACT_SUCCESS']= "Contacts syncing has done successfully";
$lang['INVALID_ARRAY']= "This is not the correct format of JSON data";
$lang['EVENT_SUCCESS']= "Event has been created successfully";
$lang['EVENT_TYPE_SUCCESS']= "Event Type has been fetched successfully";
$lang['FRIEND_SUCCESS']= "Friends list has been fetched successfully";
$lang['PERSONAL_EVENTS_SUCCESS']= "Personal events has been fetched successfully";
$lang['CONCERT_LIST_SUCCESS']= "Concert List has been fetched successfully";
$lang['MOVIE_LIST_SUCCESS']= "Movie List has been fetched successfully";
$lang['SPORT_LIST_SUCCESS']= "Sports List has been fetched successfully";
$lang['EVENT_DETAIL_SUCCESS']= "Event details has been fetched successfully";
$lang['EVENT_UPDATE_SUCCESS']= "Event has been updated successfully";
$lang['REMINDER_UPDATE_SUCCESS']= "Reminder has been updated successfully";
$lang['INVITE_FRIENDS_SUCCESS']= "Friends has been invited successfully";
$lang['NEW_FRIEND_SUCCESS']= "New friends list has been fetched successfully";
$lang['ACCEPT_INVITATION_SUCCESS'] = 'Invitation has been accepted successfully';
$lang['REJECT_INVITATION_SUCCESS'] = 'Invitation has been rejected successfully';
$lang['PENDING_EVENTS_SUCCESS']= "Pending events has been fetched successfully";
$lang['UPDATE_TOKEN_SUCCESS']= "Device token has been updated successfully";
$lang['Notification_ON_SUCCESS']= "Notification On successfully";
$lang['Notification_OFF_SUCCESS']= "Notification Off successfully";
$lang['LOGOUT_SUCCESS']= "User has been logged out successfully";
$lang['NUMBER_EXIST']= "This number is already exist";
$lang['NUMBER_UPDATED']= "Number Updated success";
$lang['OTP_MSG']= "Dear User One Time Password to verify your mobile number is ";
$lang['OTP_END_MSG']    = ' DO NOT share it with anyone.';
$lang['ADD_FAVOURITE_SUCCESS']    = 'Favourite added successfully';
$lang['REMOVE_FAVOURITE_SUCCESS']    = 'Favourite removed successfully';
$lang['NOT_INTERESTED']    = 'User does not have interest in this';
$lang['EVENT_DATE_SUCCESS']    = 'Event Dates fetched successfully';
$lang['NEWS_SUCCESS']    = 'News List fetched successfully';
$lang['VIDEO_SUCCESS']    = 'Video List fetched successfully';
$lang['GIF_SUCCESS']    = 'Gif List fetched successfully';
$lang['GIFT_SUCCESS']    = 'Gift List fetched successfully';
$lang['MOOD_SUCCESS']    = 'Mood Options fetched successfully';
$lang['HOLIDAY_LIST_SUCCESS']    = 'Holiday List fetched successfully';
$lang['BLOCKED_FRIEND']= "This user is blocked by you";
$lang['BLOCK_SUCCESS']= "This user has been blocked successfully";
$lang['UNBLOCK_SUCCESS']= "This user has been unblocked successfully";
$lang['PLAN_SUCCESS']= "Holiday has planned successfully";
$lang['GREETING_SUCCESS']= "Greeting has been created successfully";
$lang['BLOCK_LIST_SUCCESS']= "Block List fetched successfully";
$lang['GREETING_LIST_SUCCESS']= "Greeting List fetched successfully";
$lang['DELETE_SUCCESS']= "Deleted successfully";
$lang['GREETING_SHARE_SUCCESS']= "Greeting has been shared successfully";
$lang['JOKE_SUCCESS']    = 'Joke List fetched successfully';
$lang['THOUGHT_SUCCESS']    = 'Thoughts List fetched successfully';
$lang['NOTIFICATION_SUCCESS']    = 'Notification List fetched successfully';
$lang['INVITE_EVENT_MSG']    = 'Hi, You have been invited in Budfie Event. Please click on the below link for more details.'
        . BASE_URL."/share";
$lang['SHARE_ID_MISSING']    = 'Please give share id in this case';
$lang['NOTIFICATION_DELETED']    = 'Notification deleted successfully';
$lang['NOTIFICATION_COUNT']= 'Notification count fetched successfully';
$lang['NOTIFICATION_READ_SUCCESS']= 'Notification read successfully';
$lang['PUSH_SUCCESS']    = 'Push sent successfully';
$lang['NOTIFICATION_REMINDER_SUCCESS']= "Notification reminder has been created successfully";
$lang['VISIBILITY_ON_SUCCESS']= "Visibility On successfully";
$lang['VISIBILITY_OFF_SUCCESS']= "Visibility Off successfully";
$lang["INVITE_SMS"] = 'Hi, You have been invited in Budfie Event. Please click on the below link for more details. '
        .SITE_URL."/event";
$lang['HOLIDAY_UPDATE_SUCCESS']= "Holiday Plan has been updated successfully";
$lang['GREETING_COUNT_SUCCESS']= "Greeting count has been fetched successfully";
$lang["REMINDER_LIST_SUCCESS"] = "Reminders has been fetched successfully";
$lang['RECIPIENT_SUCCESS'] = "Recipient added successfully";
$lang['RECIPIENT_DETAILS_SUCCESS'] = "Recipient details has been fetched succesfully";
$lang['RECIPIENT_LIST_SUCCESS']    = 'Recipient List fetched successfully';
$lang['IPL_TEAM_FETCH_SUCCESS'] = "Ipl teams data fetched successfully";
$lang['COMPLETE_SUCCESS'] = "Reminder has been completed successfully";
$lang['VERSION_SUCCESS'] = "Version details has been fetched succesfully";
//Admin 
$lang['success'] = 'Success!';
$lang['error'] = 'Error!';
$lang['success_prefix'] = '<label class="alert alert-success alert-dismissable" style="margin-bottom:0px;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button><strong>Success! </strong>';
$lang['success_suffix'] = '</label>';
$lang["login_success"] = '<label class="alert alert-success alert-dismissable" style="margin-bottom:0px;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button><strong>Welcome! You have successfully logged in </strong>';
$lang['error_prefix'] = '<div class="alert alert-danger alert-dismissable" style="margin-bottom:0px"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button><strong>Error!</strong> ';
$lang['error_suffix'] = '</div>';
$lang['profile_update'] = 'Profile updated successfully';
$lang["password_updated"] = "Change password successful";
$lang["old_password_mismatch"] = "Old password does not match";
$lang['old_pass'] = 'Ols password';
$lang['new_password'] = 'New password';
$lang['confirm_password'] = 'Confirm password';
$lang['password'] = 'password';
$lang['name_missing'] = 'Please enter the name';
$lang['email'] = 'email';
$lang['invalid_email_password'] = 'Enter valid email address or password';
$lang['invalid_email'] = 'This email is not registered with us';
$lang['reset_email'] = 'A reset password link has been sent to your registered email address';
$lang['password_changed'] = 'Password has been reset successfully';
$lang["category_updated"] = "Event category has been updated successfully";
$lang["category_added"] = "Event Category has been added successfully";
$lang["genre_updated"] = "Movie genre has been updated successfully";
$lang["genre_added"] = "Movie genre has been added successfully";
$lang["movie_updated"] = "Movie details has been updated successfully";
$lang["movie_added"] = "Movie has been added successfully";
$lang["news_updated"] = "News has been updated successfully";
$lang["news_added"] = "News has been added successfully";
$lang["gif_updated"] = "GIF has been updated successfully";
$lang["gif_added"] = "GIF has been added successfully";
$lang["joke_updated"] = "Joke has been updated successfully";
$lang["joke_added"] = "Joke has been added successfully";
$lang["thought_updated"] = "Thought has been updated successfully";
$lang["thought_added"] = "Thought has been added successfully";
$lang["only_image"] = "Please choose an image only";
$lang["gift_updated"] = "Gift has been updated successfully";
$lang["gift_added"] = "Gift has been added successfully";
$lang["match_updated"] = "Match has been updated successfully";
$lang["match_added"] = "Match has been added successfully";
$lang["greeting_updated"] = "Greeting has been updated successfully";
$lang["greeting_added"] = "Greeting has been added successfully";
$lang["video_updated"] = "Video has been updated successfully";
$lang["video_added"] = "Video has been added successfully";
$lang["invitation_accept"] = "You have accepted invitation successfully";
$lang["EVENT_DELETED_SUCCESS"] = "Event Deleted successfully";
$lang["version_updated"] = "Version has been updated successfully";
$lang["version_added"] = "Version has been added successfully";


$lang["trending_updated"] = "Trending has been updated successfully";
$lang["trending_added"] = "Trending has been added successfully";
$lang["hotevent_added"] = "Hot Event has been added successfully";
$lang["hotevent_already_added"] = "Hot Event already added for this date";


$lang["appad_added"] = "Ad has been added successfully";
$lang["appad_updated"] = "Ad has been updated successfully";
$lang["only_video"] = "Please choose an video only";

//Admin CMS
$lang['title'] = 'Title';
$lang['page_desc'] = 'Page Description';
$lang['status'] = 'Status';
$lang['page_added'] = 'Your page has been added successfully';
$lang['page_updated'] = 'Your page has been updated successfully';
$lang['try_again'] = 'Please try again';


