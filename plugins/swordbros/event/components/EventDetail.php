<?php

namespace Swordbros\Event\Components;

use Auth;
use Flash;
use Input;
use Mail;
use Media\Classes\MediaLibrary;
use RainLab\User\Models\User;
use RainLab\User\Models\UserLog;
use Redirect;
use Site;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Booking\Models\BookingRequestHistoryModel;
use Swordbros\Booking\models\BookingRequestModel;
use Swordbros\Event\Models\EventModel;
use Cms\Classes\ComponentBase;
use Swordbros\Setting\Models\SwordbrosSettingModel;
use ValidationException;
use Validator;

/**
 * BackendLink component
 */
class EventDetail extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Swordbros Event Detail',
            'description' => 'Show a Swordbros Event.'
        ];
    }
    /* they should be registered in Plugin.php
    public function registerMailTemplates()
    {
        return [
            'booking_request_new' => 'swordbros.event::mail.booking_request_new',
        ];
    }*/
    public function onRun()
    {
        // $this->page['mediaUrl'] = MediaLibrary::url('/'); // not necessary
        $this->page['title'] = __('event.events');
        $this->event = $this->page['event'] = $this->loadRecord();
    }

    protected function loadRecord(): EventModel
    {
        $id = $this->param('id');
        if (!strlen($id)) {
            return "1";
        }
        $row = EventModel::findOrFail((int)$id);

        return $row;
    }

    public function onSubmitBookingForm()
    {
        $rules = [
            'booking_request.first_name' => 'required',
            'booking_request.last_name' => 'required',
            'booking_request.email' => 'required|email',
            'booking_request.phone' => 'required',
            'consent' => 'accepted',
            // 'secondname' => 'size:0',
        ];

        $messages = [
            'booking_request.first_name' => trans('First name is required'),
            'booking_request.last_name' => trans('Last name is required'),
            'booking_request.email.required' => trans('Email is required'),
            'booking_request.phone' => trans('Phone is required'),
            'accepted' => trans('Accept Privacy rules'),
            // 'secondname.size' => translate('secondname.size'),
        ];

        $validation = Validator::make(post(), $rules, $messages);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $booking_request = Input::get('booking_request');
        if ($booking_request) {
            $createuser = $this->getUseridBookingRequest($booking_request);
            if ($createuser['message']) {
                Flash::warning($createuser['message']);
                return;
            }
            $booking_request['user_id'] = $createuser['userId'];
            $bokingRequestModel = new BookingRequestModel();
            $bokingRequestModel->fill($booking_request);
            $bokingRequestModel->status = 0;
            $bokingRequestModel->otp = md5(\Str::random(64));
            $bokingRequestModel->save();
            $data = $bokingRequestModel->toArray();

            $data['subject'] = 'Subject';
            $data['content'] = 'Content';

            $site_id = Site::getSiteIdFromContext();
            $data['send_email'] = $data['email'];

            Mail::send('swordbros.booking_request_new-' . $site_id, $data, function ($message) use ($data) {
                $message->to($data['send_email'], $data['first_name']);
            });

            $data['requestApprove'] = route('request-approve', ['otp' => $bokingRequestModel->otp]);
            $data['requestDecline'] = route('request-decline', ['otp' => $bokingRequestModel->otp]);
            $emails = Amele::getAlertEmails();
            foreach ($emails as $email) {
                $email = preg_replace('/[^a-zA-Z0-9._%+-@]/', '', $email);
                if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
                    $data['send_email'] = $email;
                    Mail::send('swordbros.booking_request_new-' . $site_id, $data, function ($message) use ($data) {
                        $message->to($data['send_email'], $data['first_name']);
                    });
                }
            }
            Amele::addBookingRequestHistory($data['id'], $data['send_email'] . ' adresine email gönderildi. ');
            //Flash::success('Booking Created!');
            return Redirect::to(url('/booking/thankyou', ['id' => $bokingRequestModel->id]));
        } else {
            Flash::warning('booking_request not posted');
        }
    }
    private function getUseridBookingRequest($data)
    {
        $result = [
            'userId' => 0,
            'message' => '',
        ];
        $user = Auth::getUser();
        if ($user) {
            $result['userId'] = $user->id;
            return $result;
        }
        $user = User::where(['email' => $data['email']])->first();
        if ($user) {
            $result['message'] = trans('booking.alert.email_in_use_please_login');
            return $result;
        }
        $existRequest = BookingRequestModel::where(['email' => $data['email'], 'event_id' => $data['event_id']])->exists();
        if ($existRequest) {
            $result['message'] = trans('booking.alert.request_allready_exists');
            return $result;
        }
        $create_user_booking_request = SwordbrosSettingModel::swordbros_setting('create_user_booking_request');
        if ($create_user_booking_request) {
            $user = new User();
            $user->email = $data['email'];
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            //$user->phone = $data['phone'];
            $user->password =  \Str::random(16);
            $user->save();
            if ($user->id) {
                $result['userId'] = $user->id;
                UserLog::createRecord($user->getKey(), 'User Created', [
                    'user_full_name' => $user->full_name,
                    'old_value' => '',
                    'new_value' => $data['email']
                ]);
                return $result;
            }
        }
        return $result;
    }
}
