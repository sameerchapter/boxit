<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingData;
use App\Models\QaChecklist;
use App\Models\MarkoutChecklist;
use App\Models\ProjectQaChecklist;
use App\Models\ProjectStatusLabel;
use App\Models\ProjectStatus;
use App\Jobs\BookingEmailJob;
use App\Models\ForemanTemplates;
use App\Models\SafetyPlan;
use Auth;
use DB;
class ForemanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       
        $departments = Department::all();
        $foreman = User::find(Auth::id());
        return view('formancalender', compact('departments', 'foreman'));
    }

    public  function daysInMonth($iMonth, $iYear)
    {
        return cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);
    }

    public function calender(Request $request)
    {
        $dates = $request->get('dates');
        $year = $request->get('year');
        $requested_month = $request->get('month') + 1;
        $html = '';
        foreach ($dates as $date) {
            $html .= '<div class="foo pd-boxes">';
            if ($date['thisMonth'] != 1) {
                if ($date['day'] >= 25)
                    $month = $requested_month - 1;
                else
                    $month = $requested_month + 1;
            }
            else
            {
              $month=$requested_month;
            }
            $booking_date = date('Y-m-d', strtotime("$year-$month-" . $date['day']));
            
            $department_id = array(2, 3, 4, 5, 6, 7, 8, 9, 10);
            foreach ($department_id as $id) {
                $booking_data = BookingData::whereHas('booking', function($q) {
                    $q->where('foreman_id',Auth::id());
                })->where(array('department_id' => $id))->whereDate('date', '=', $booking_date)
                    ->get();
                $b_id = '';
                $html.="<div class='booked_div'>";
             foreach ($booking_data as $boo) {
                    $address = implode(' ', array_slice(explode(' ', $boo->booking->address), 0, 3));
                    $style='';
                    switch ($boo->status) {
                        case '0':
                            $class = "orange_box show_booking";
                            $style='background: '.$boo->booking->pending_background_color.';color: '.$boo->booking->pending_text_color.' !important;border-left: 1px solid '.$boo->booking->pending_text_color.';border-bottom: 1px solid '.$boo->booking->pending_text_color.';';
                            break;
                        case '1':
                            $class = "green_box show_booking";
                            $style='background: '.$boo->booking->confirm_background_color.';color: '.$boo->booking->confirm_text_color.' !important;border-left: 1px solid '.$boo->booking->confirm_text_color.';border-bottom: 1px solid '.$boo->booking->confirm_text_color.';';
                            break;
                        case '2':
                            $class = "red_box show_booking";
                            break;
                        default:
                            $class = "show_booking";
                    }
                    $b_id = $boo->booking_id;
                    $html.="<span class='$class' style='$style' data-id='" . $b_id . "'>$address</span>";
                }
                $html .= "</div>";
            }
            $html .= "</div>";
        }
        return $html;
    }
    
    public function monthly_calender(Request $request)
    {
        $year = $request->get('year');
        $requested_month = $request->get('month') + 1;
        $firstDay = $dayofweek = date('w', strtotime($year . "-" . $requested_month));
        $date = 1;
        $html = '';
        for ($i = 0; $i < 6; $i++) {
            // creates a table row
            $html .= '<div class="foo_monthly pd-boxes">';

            //creating individual cells, filing them up with data.
            for ($j = 0; $j < 7; $j++) {
                if ($i === 0 && $j < $firstDay) {
                    $html .= '<div class="booked_div_monthly"><span class="week_count"></span></div>';
                } else if ($date > $this->daysInMonth($requested_month, $year)) {
                    $html .= '<div class="booked_div_monthly"><span class="week_count"></span></div>';
                    continue;
                } else {
                    $current = strtotime(date("Y-m-d"));
                    $today_date    = strtotime("$year-$requested_month-$date");
                    $datediff = $today_date - $current;
                    $class='';
                    if($datediff==0)
                    {
                      $class=" active-day-month";
                    }
                    $inner_html = '<span data-id="" class="week_count'.$class.'">' . $date . '</span>';
                    $booking_date = date('Y-m-d', strtotime($year . "-" . $requested_month . "-" . $date));
                    $booking_datas = BookingData::whereHas('booking', function($q) {
                        $q->where('foreman_id',Auth::id());
                    })->whereDate('date', '=', $booking_date)
                        ->get();
                    foreach ($booking_datas as $booking_data) {
                        if(!empty($booking_data->booking))
                        {
                        $address = implode(' ', array_slice(explode(' ', $booking_data->booking->address), 0, 3));
                        $dep=$booking_data->department->title;
                        $style='';
                        switch ($booking_data->status) {
                            case '0':
                                $class = "orange_bullet monthly_booking";
                                $style='color:'.$booking_data->booking->pending_text_color;
                                break;
                            case '1':
                                $class = "green_bullet monthly_booking";
                                $style='color:'.$booking_data->booking->confirm_text_color;
                                break;
                            case '2':
                                $class = "red_bullet monthly_booking";
                                break;
                            default:
                                $class = "monthly_booking";
                        }
                        $b_id = $booking_data->booking_id;
                        $inner_html .= "<span class='$class show_booking' style='$style' data-id='" . $b_id . "'>$dep:$address</span>";
                      }
                    }

                    $html .= '<div class="booked_div_monthly">' . $inner_html . '</div>';
                    $date++;
                }
            }

            $html .= '</div>';
        }
        echo  $html;
    }

    public function modal_data(Request $request)
    {
        $id = $request->get('id');
        $booking = Booking::find($id);
        $booking_data = $booking->BookingData;
        $html = '<div class="row">
								<div class="col-md-6" style="border-right: 1px solid #E7E7E7;">
									<div class="pods confirmed-txt pop-flex">
										<p>Foreman</p>
										<span>' . ucfirst($booking->foreman->name) . '</span>
									</div>';
         foreach($booking_data->slice(1,4) as $res)
         {
            $title=$res->department->title;
            $booking_date=$res->date;
            switch ($res->status) {
                case '0':
                    $class = "pending-txt";
                    $status="Pending";
                    break;
                case '1':
                    $class = "confirmed-txt";
                    $status="Confirmed";
                    break;
                case '2':
                    $class = "cancelled-txt";
                    $status="On hold";
                    break;
                default:
                    $class = "";
                    $status="";

            }
        
        $html .='<div class="steel  pop-flex '.$class.'">
										<p>'.$title.'</p>
										<span>'.date('d/m/Y h:i A', strtotime($booking_date)).' - '.$status.'</span>
									</div>
									';
         }
         $html .=		'</div><div class="col-md-6">';
         foreach($booking_data->slice(5) as $res)
         {
            $title=$res->department->title;
            $booking_date=$res->date;
            switch ($res->status) {
                case '0':
                    $class = "pending-txt";
                    $status="Pending";
                    break;
                case '1':
                    $class = "confirmed-txt";
                    $status="Confirmed";
                    break;
                case '2':
                    $class = "cancelled-txt";
                    $status="On hold";

                    break;
                default:
                    $class = "";
                    $status="";

            }
						$html.='			<div class="pods '.$class.' pop-flex">
										<p>'.$title.'</p>
										<span>'.date('d/m/Y h:i A', strtotime($booking_date)).' - '.$status.'</span>
									</div>';}
									
							$html.='</div></div>';
							
        return array('address' => $booking->address, 'floor_type'=>$booking->floor_type,'floor_area'=>$booking->floor_area,'building_company'=>$booking_data[0]->department_id=='1'?$booking_data[0]->contact->title:'NA', 'notes' => $booking->notes!=''?$booking->notes:'NA','notes' => $booking->notes, 'html' => $html);
    }

    public function check_list()
    {
        $projects=Booking::where(array('foreman_id'=>Auth::id()))->get();
        return view('foreman-project',compact('projects'));

    }

    public function renderproject(Request $request )
    {   

        $project=Booking::find($request->get('id'));
        $department_ids=BookingData::where('booking_id',$request->get('id'))->pluck('department_id');
        $markout_checklist=$project->MarkoutChecklist;
        $safety=$project->SafetyPlan;
        $qaChecklist=QaChecklist::all();
        $ProjectStatusLabel=ProjectStatusLabel::where(function ($query) use ($department_ids) {
                  $query->where('department_id', '=', '')
                  ->orWhereIn('department_id',$department_ids);
                })
                  ->get();
        return view('foreman-single-project',compact('safety','project','qaChecklist','markout_checklist','ProjectStatusLabel'))->render();
    }

    public function storeQaChecklist(Request $request )
    {   
        $res=ProjectQaChecklist::where('project_id',$request->get('project_id'))->delete();
        $initial=$request->get('initial');
        $office_use=$request->get('office_use');
        $project_id=$request->get('project_id');
        $insert_array=[];
        $final_array=[];
        foreach($initial as $key=>$val)
        {
            $insert_array['project_id']=$project_id;
            $insert_array['qa_checklist_id']=$key;
            $insert_array['initial']=$val!=null?$val:'';
            $insert_array['office_use']=$office_use[$key]!=null?$office_use[$key]:'';
            $final_array[]=$insert_array;
        }
        ProjectQaChecklist::insert($final_array);
        return redirect()->to('check-list/')->with('succes_msg', 'Onsite & QA Checklist saved successfuly');

    }

    
    public function storeMarkoutlist(Request $request )
    {   
        $project_id=$request->get('project_id');
        $res=MarkoutChecklist::where('project_id',$project_id)->delete();
        $final_array=$request->get('markout_data');
        $final_array['project_id']=$project_id;
        MarkoutChecklist::insert($final_array);
        return redirect()->to('check-list/')->with('succes_msg', 'Markout Checklist saved successfuly');

    }

    public function changeStatus(Request $request)
    {
        $matchThese = ['project_id'=>$request->get('project_id'),'status_label_id'=>$request->get('status_label_id')];
        $data=['status'=>$request->get('status')];
        if($request->get('status')=='0' && $request->get('status_label_id')=='10')
        {
            $data['reason']=$request->get('reason');
        }else
        {
            $data['reason']='';
        }
      
        ProjectStatus::updateOrCreate($matchThese,$data);
        $email_template=ForemanTemplates::where(array('status'=>$request->get('status'),'project_status_label_id'=>$request->get('status_label_id')))->get();
      if(count($email_template)>0)
      {
        $details['to'] = \config('const.admin1');
        $details['name'] = 'test';
        $details['subject'] = $email_template[0]->subject;
        $details['body'] =$email_template[0]->body;
        dispatch(new BookingEmailJob($details));
        $details['to'] = \config('const.admin2');
        dispatch(new BookingEmailJob($details));
      }  
        return true;
    } 

    public function safety_plan(Request $request)
    {
      $data=$request->except('_method', '_token');
      $post_data=$data['safety_plan'];
      SafetyPlan::updateOrCreate(['project_id'=>$request->get('project_id')],$post_data);
      return redirect()->to('check-list/')->with('succes_msg', 'Safety plan saved successfuly');

    }
}
