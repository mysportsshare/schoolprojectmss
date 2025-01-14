<?php

namespace App\Http\Controllers;

use App\Event;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Ticket;
use Rap2hpoutre\LaravelStripeConnect\StripeConnect;
use Illuminate\Support\Facades\Storage;
use App\Category;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $events = Event::where([
            ['is_private', 0],
            ['end_date', '<=', today(),]
        ])->get()->orderBy('end_date', 'DESC');
        return $events;
    }

    /**
     * Show the form for creating a new resoufrce.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.event-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $now = Carbon::now();
        $startDate = Carbon::createFromFormat("Y-m-d", $request->start_date);
        $endDate = Carbon::createFromFormat("Y-m-d", $request->end_date);
        $start_time = $request->start_hour + $request->am_pm;
        $end_time = $request->end_hour + $request->end_am_pm;
        $categories = Category::get();
       
        $start_date = Carbon::create($startDate->format('Y'), $startDate->format('m'), $startDate->format('d'), $start_time, $request->start_minute, 00);
        // $end_date = Carbon::create($endDate->format('Y'), $endDate->format('m'), $endDate->format('d'), $end_time, $request->end_minute, 00);
        // $extension = $request->file('event_image')->getClientOriginalExtension();
        // $path = $request->event_image->store('event_image');
        
        $event = new Event;
        $event->title = $request->title;
        $event->state = $request->location_state ?? ' ';
        $event->zip = $request->location_post_code ?? ' ';;
        $event->city = '';
        $event->start_sale_date = $start_date;
        $event->end_sale_date = $end_date ?? $now;
        $event->image = $path ?? '';
        $event->event_type = $request->event_type;
        $event->website = '';
        $event->venu_id = '0';
        $event->description = $request->description;
        $event->event_image = $request->event_image;
        $event->venu_name = $request->location_venue_name;
        $event->street = $request->location_address_line_1 ?? '';
        $event->user_id = $request->organiser_id ?? \Auth::user()->id;
        $event->start_date = $start_date;
        $event->end_date = $end_date ?? $now;
        $event->is_private = 0;
        $event->save();
        
        $startDate = $event->start_date->year;
        $startHour = $event->start_date->hour;
        $startMinute = $event->start_date->minute;
        
        return view('events.event-create2', compact('event','categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event, $id)
    {
        //
        $event = Event::find($id);
        $ticket = Ticket::where('event_id', $id)->get();
        $user = $event->users->name;
        $date = Carbon::parse($event->created_at)->diffForHumans();
        
        return view('events.event-view', compact('event', 'ticket', 'user', 'date'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $event = Event::find($id);
        $ticket = Ticket::where('event_id', $id)->get();
        $user = $event->users->name;
        $date = Carbon::parse($event->created_at)->diffForHumans();
        
        return view('events.event-create2', compact('event', 'ticket', 'user', 'date'));    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }

    public function addTicket(Request $request)
    {
        $event = Event::find($request->id);
        $tickets = new Ticket;
        $tickets->title = $request->ticket_title;
        $tickets->price = $request->price;
        $tickets->quantity_available = $request->ticket_qty;
        $tickets->user_id = \Auth::id();
        $tickets->account_id = \Auth::id();
        $tickets->event_id = $request->id;
        $tickets->description = '';
        $tickets->save();

        
        return redirect('/view-event/'.$request->id);

    }

    public function makePayment(Request $request)
    {
        //get user id of the person that created the event
        $vendor = User::find($request->user_id);
        //get the id of the current user
        $customer = \Auth::id();
        //get stripe token from 
        $token = $request->stripeToken;
        // StripeConnect::createAccount($vendor);
        StripeConnect::transaction($token)
            ->amount(1000, 'usd')
            ->fee(50)
            ->from($customer)
            ->to($vendor)
            ->create();
           $name = $request->stripeBillingName;
           $email = $request->stripeEmail;
           return view('events.receipt', compact('name', 'email'));
        dd($request->all());
    }

    public function makeVendor()
    {
        //set the vendor to the current user that requested
        $vendor = User::find(\Auth::id())->first();
        StripeConnect::createAccount($vendor);
        return view('/');
    }

    public function search(Request $request)
    {
        // $search = Event::where('end_date', '>', now())->orderBy('end_date', 'DESC');
        $search = Event::all();
       
        if(!empty($request->search))
        {
            $search->where('title', 'like', '%'.$request->search.'%');
        }
        if(!empty($request->category))
        {
            $search->where('category_id', $request->category);
        }
        return view('events.events-page', compact('search'));
    }

    public function category($id)
    {
        // $search = Event::where('end_date', '>', now())->orderBy('end_date', 'DESC');
        $search = Event::where('category_id', $id)->get();
       
        
        return view('events.events-page', compact('search'));
    }


}
