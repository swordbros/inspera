<?php namespace Swordbros\Event\Components;

use Flash;
use Input;
use Mail;
use Media\Classes\MediaLibrary;
use Redirect;
use Site;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Booking\Models\BookingRequestHistoryModel;
use Swordbros\Booking\models\BookingRequestModel;
use Swordbros\Event\Models\EventModel;
use Cms\Classes\ComponentBase;

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
    public function registerMailTemplates()
    {
        return [
            'booking_request_new' => 'swordbros.event::mail.booking_request_new',
        ];
    }
    public function onRun()
    {
        $this->page['mediaUrl'] = MediaLibrary::url('/');
        $this->page['title'] = __('swordbros.event::lang.plugin.events');
        $this->event = $this->page['event'] = $this->loadRecord();
    }
    protected function loadRecord()
    {
        if (!strlen($this->param('id'))) {
            return "1";
        }
        $model = new EventModel();
        $row =  $model->where('id', '=', (int)$this->param('id'))->first();
        return $row;
    }
    public function onSubmitBookingForm(){
        $booking_request = Input::get('booking_request');
        if($booking_request){
            $bokingRequestModel = new BookingRequestModel();
            $bokingRequestModel->fill($booking_request);
            $bokingRequestModel->status = 0;
            $bokingRequestModel->save();
            $data = $bokingRequestModel->toArray();
            $data['subject'] = 'Subject';
            $data['content'] = 'Content';

            $site_id = Site::getSiteIdFromContext();
            Mail::send('swordbros:booking_request_new-'.$site_id, $data, function ($message) use($data) {
                $message->to($data['email'], $data['first_name']);
            });
            Amele::addBookingRequestHistory($bokingRequestModel->id, 'Rezervasyon isteği oluşturuldu ve '.$data['email'].' adresine email Gönderildi');
            Flash::success('Booking Created!');
            return Redirect::to(url('/booking/thankyou', ['id'=>$bokingRequestModel->id]));

        } else{
            Flash::warning('booking_request not posted');
        }
    }

}
