<style>
    @import "bourbon";

    .cd-switch {
        padding: 50px 0;
        text-align: center;
    }

    .radio_label {
        cursor: pointer;
        text-transform: uppercase;
        border: 1px solid #3d4349;
        width: 80px;
        padding: 10px 0;
        text-align: center;
        display: inline-block;
        transition: all 0.4s;
        margin-right: 10px;
    }

    .switch {
        display: inline-block;
        z-index: 1;
    }

    .switch input[type="radio"] {
        visibility: hidden;
        position: absolute;
        height: 100%;
    }

    .switch input[type="radio"].yes:checked~label[for^="yes_"] {
        background-color: #172b4d;
        color: #fff
    }

    .switch input[type="radio"].no:checked~label[for^="no_"] {
        background-color: #172b4d;
        color: #fff
    }


    input[type="range"] {
        width: 30px;
    }


    .screen-reader-only {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }

    .qa_checklist table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    .qa_checklist td,
    th {
        border: 1px solid #c9ced6;
        text-align: left;
        padding: 8px;
    }



    .qa_checklist {
        margin: 20px 10px;
    }

    .safety_plan input {
        border-left: none;
        border-right: none;
        border-top: none;
        border-bottom: 0.5px solid black;
        outline: none;
    }

    .marg-lr-none {
    margin: 20px 0px;
}
.bor-none td {
    border: none;
    text-align: center;
}
.canvas-size
{
    width:100%;
    height:150px;
}
    @media screen and (min-width:768px) {
        .table .paid-t {
            padding-top: 38px !important;
        }
    }
</style>
<div class="modal fade"  role="dialog" id="reason_form">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Council Inspection</h5>
        <button type="button" class="close" data-bs-dismiss="modal" onclick="window.location.reload()" aria-label="Close">
          <span aria-hidden="true">??</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div style="display:none" id="modal_contact_id"></div>
          <div class="form-group">
          <label for="reason" class="col-form-label">Reason:</label>
            <textarea placeholder="Please provide details about inspection failure here."  class="form-control" id="reason"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" data-project="{{$project->id}}" id="submit_reason" class="btn btn-secondary">Save</button>
      </div>
    </div>
  </div>
</div>
<div class="card-new">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-head">
                    <span>Project: <u>{{$project->address}}</u></span>
                </div>
            </div>
        </div>
        <div class="project-back-btn">

            <button type="button" id="back" class="save_button btn btn-secondary">Back</button>
        </div>

        <br />
        <br />
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs center-contain" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button style="color:#172b4d" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">Project Status</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button style="color:#172b4d" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="true">Onsite & QA Checklist</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button style="color:#172b4d" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3" aria-selected="true">Mark Out Checklist</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button style="color:#172b4d" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab4" type="button" role="tab" aria-controls="tab4" aria-selected="true">Safety Plan
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div style="padding:2%" d class="tab-pane fade show active paid-l-none" id="tab1" role="tabpanel" aria-labelledby="1-tab">

                        <div class="row">

                            <table class="table">

                                <tbody>
                                    @foreach($ProjectStatusLabel as $label)
                                    @php
                                    $project_status= $label->ProjectStatus($project->id)->get();
                                    if(count($project_status)>0)
                                    {
                                    $yes_checked=$project_status[0]->status==1?'checked':'';
                                    $no_checked=$project_status[0]->status==0?'checked':'';
                                    }else
                                    {
                                    $yes_checked="";
                                    $no_checked="";
                                    }
                                    @endphp
                                    <tr>
                                        <td>{{$label->label}}</td>
                                        <td>
                                            <div class="switch">
                                                <input type="radio" {{$yes_checked}} class="project_status yes" id="yes_{{$label->id}}" data-id="{{$label->id}}" data-project="{{$project->id}}" name="status[{{$label->id}}]" value="1">
                                                <label class="radio_label" for="yes_{{$label->id}}">{{$label->id=="10"?"Passed":"Yes"}}</label>
                                                <input type="radio" {{$no_checked}} id="no_{{$label->id}}" class="project_status no"  data-id="{{$label->id}}" data-project="{{$project->id}}" name="status[{{$label->id}}]" value="0">
                                                <label class="radio_label" for="no_{{$label->id}}">{{$label->id=="10" || $label->id=="9" ? $label->id=="10"?"Failed":"NA":"No"}}</label>
                                                @if(count($project_status)>0)
                                                {!!$project_status[0]->reason!=''?'<a href="#" data-toggle="tooltip" title="'.$project_status[0]->reason.'" ><i class="fa fa-eye"></i></a>':''!!}
                                               @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div>

                    </div>
                    <div style="padding:15px;" d class="tab-pane fade paid-l-none" id="tab2" role="tabpanel" aria-labelledby="2-tab">
                        <form action="{{URL('/qa_checklist')}}" method="post" id="qa_form">
                            @csrf
                            <h5>Onsite & QA Checklist</h5>
                            <input type="hidden" name="project_id" value="{{$project->id}}">
                            <div class="qa_checklist marg-lr-none">
                                <!-- <div class="row onsite_label">
                                   <div class="col-md-6"></div>
                                   <div class="col-md-3">Initial</div>
                                   <div class="col-md-3">Office Use</div> 
                                </div> -->
                                <table style="width:100%">
                                <tr class="bor-none">
                                    <td></td>
                                    <td>Initial</td>
                                    <td>Office Use</td>
 

                                </tr>
                                    @foreach($qaChecklist as $res)
                                    <tr>
                                        <td>{{$res->subject}}</td>
                                        @php
                                        $project_qa= $res->ProjectQaChecklist($project->id)->get();
                                        if(count($project_qa)>0)
                                        {

                                        $initial=$project_qa[0]->initial;
                                        $office_use=$project_qa[0]->office_use;
                                        }else
                                        {
                                        $initial="";
                                        $office_use="";
                                        }
                                        @endphp
                                        <td class="table-w"><input type="text" value="{{$initial}}" name="initial[{{$res->id}}]"></td>
                                        <td class="table-w"><input type="text" value="{{$office_use}}" name="office_use[{{$res->id}}]"></td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            <canvas id="onsite_canvas" class="canvas-size" style="border: 1px solid black;"></canvas>
                            <button type="button" data-id="onsite_signature" class="btn btn-sm clear" style="color:#fff;background-color:#172b4d">Clear</button>
                            <div style="float:right"><button type="submit" class="btn btn-secondary">Save</button>
                        </form>
                    </div>
                </div>
                <div style="padding:3%" d class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="3-tab">
                    <form action="{{URL('/markout_checklist')}}" method="post" id="markout_form">
                        @csrf
                        <h5>Mark Out Checklist</h5>
                        <input type="hidden" name="project_id" value="{{$project->id}}">
                        <div style="margin:5%">
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label "><strong>Date:</strong></label>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" name="markout_data[date]" value="{{ $markout_checklist!=null ? $markout_checklist->date : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label "><strong>Address:</strong></label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="markout_data[address]" value="{{ $markout_checklist!=null ? $markout_checklist->address : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label "><strong>Housing Company:</strong></label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="markout_data[housing_company]" value="{{ $markout_checklist!=null ? $markout_checklist->housing_company : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label ">Power</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="markout_data[power]" value="{{ $markout_checklist!=null ? $markout_checklist->power : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label ">Site fenced</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="markout_data[site_fenced]" value="{{ $markout_checklist!=null ? $markout_checklist->site_fenced : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label ">Toilet</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="markout_data[toilet]" value="{{ $markout_checklist!=null ? $markout_checklist->toilet : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label ">Water</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="markout_data[water]" value="{{ $markout_checklist!=null ? $markout_checklist->water : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label ">Boundary Pegs all in Place</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="markout_data[boundary_pegs]" value="{{ $markout_checklist!=null ? $markout_checklist->boundary_pegs : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-12 col-form-label col-form-label ">Draw in here what???s missing</label>

                                <div class="col-md-6">
                                    @if(!empty($markout_checklist->draw_in))
                                    <img src="{{$markout_checklist->draw_in}}" id="drawin_sign" width="400">
                                    @else
                                    <canvas id="drawin_canvas" width="600" height="300" style="border: 1px solid black;"></canvas>
                                    <button type="button" data-id="drawin_signature" class="btn btn-sm clear" style="color:#fff;background-color:#172b4d">Clear</button>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label ">Boundary Dimensions Back Checked - are they Correct</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="markout_data[boundary_dimension]" value="{{ $markout_checklist!=null ? $markout_checklist->boundary_dimension : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label ">FFL Set and Marked on Fence
                                </label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="markout_data[ffl_set]" value="{{ $markout_checklist!=null ? $markout_checklist->ffl_set : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-md-6 col-form-label ">FFL Height out of Ground
                                </label>
                                <div class="col-md-2">
                                    <input placeholder="min" type="number" class="form-control" name="markout_data[ffl_height_min]" value="{{ $markout_checklist!=null ? $markout_checklist->ffl_height_min : '' }}">
                                </div>
                                <div class="col-md-2">
                                    <input placeholder="max" type="number" class="form-control" name="markout_data[ffl_height_max]" value="{{ $markout_checklist!=null ? $markout_checklist->ffl_height_max : '' }}">
                                </div>
                            </div>
                        </div>

                        @if(!empty($markout_checklist->foreman_sign))
                        <img src="{{$markout_checklist->foreman_sign}}" id="markout_sign" width="200">
                        @else
                        <canvas id="markout_canvas" style="border: 1px solid black;"></canvas>
                        <button type="button" data-id="markout_signature" class="btn btn-sm clear" style="color:#fff;background-color:#172b4d">Clear</button>
                        @endif
                        <div style="float:right"><button type="submit" class="btn btn-secondary">Save</button>
                    </form>
                </div>
            </div>
            <div style="padding:3%" d class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="4-tab">
                <h5>Safety plan</h5>
                <br>
                <form action="{{URL('/safety-plan')}}" id="safety_form" method="post">
                    @csrf
                    <input type="hidden" name="project_id" value="{{$project->id}}">
                    <div class="row safety_plan">
                        <p>This plan is to be completed with all workers prior to works beginning. All personnel must complete a site induction</p>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="2">Site / Address: {{$project->address}}</th>
                                    <th colspan="2">Client: <input type="text" style="width:70%" name="safety_plan[client]" value="{{ $safety!=null ? $safety->client : '' }}"></th>
                                </tr>
                                <tr>
                                    <th style="width:25%">Completed By: Jimmy</th>
                                    <th style="width:35%">Date: <input type="date" style="width:70%" name="safety_plan[date]" value="{{ $safety!=null ? $safety->date : '' }}"></th>
                                    <th style="width:20%">Time In: <input type="text" style="width:40%" name="safety_plan[time_in]" value="{{ $safety!=null ? $safety->time_in : '' }}"></th>
                                    <th style="width:20%">Time Out: <input type="text" style="width:40%" name="safety_plan[time_out]" value="{{ $safety!=null ? $safety->time_out : '' }}"></th>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="6" style="text-align:center;background-color:#c9ced6;">EMERGENCY INFORMATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th rowspan="2" scope="rowgroup">Locations of
                                        Emergency
                                        Provisions:</th>
                                    <th>First Aid Kit
                                    </th>
                                    <th>Extinguisher
                                    </th>
                                    <th>Evacuation Assembly Point
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        Site Vehicle
                                    </td>
                                    <td>
                                        Site Vehicle
                                    </td>
                                    <td>
                                        At Site Entrance
                                    </td>
                                </tr>
                                <tr>
                                    <th rowspan="2" scope="rowgroup">Key Emergency
                                        Contacts:</th>
                                    <td>
                                        Emergency Response Dial: 111
                                    </td>
                                    <td>
                                        Ch Hospital 03 364 0270
                                    </td>
                                    <td>
                                        Andy Knight 027 702 1055
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Moorhouse Medical: 03 365 7900
                                    </td>
                                    <td>
                                        24 Hr Medical: 03 365 7777
                                    </td>
                                    <td>
                                        Hayden Vessey 027 672 1812
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="8" style="background-color:#c9ced6;">1.0 SITE SET UP / FACILITIES
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="col" style="width: 10%">#
                                    </th>
                                    <th style="width: 50%">ITEM
                                    </th>
                                    <th style="width: 10%">&#10004/&#x2717
                                    </th>
                                    <th style="width: 30%">
                                        NOTES / ACTIONS
                                    </th>
                                </tr>
                                <tr>
                                    <td>1.1</td>
                                    <td>
                                        Is there safe access to the site? (clear / level / no overhead lines)
                                    </td>
                                    <td class="paid-t">
                                        <input type="radio" <?php if (!empty($safety)) {
                                                                if ($safety->safe_access_tick == '1') {
                                                                    echo "checked";
                                                                }
                                                            }  ?> value="1" name="safety_plan[safe_access_tick]"><label>&#10004</label>
                                        <input class="marg-l" type="radio" <?php if (!empty($safety)) {
                                                                                if ($safety->safe_access_tick == '0') {
                                                                                    echo "checked";
                                                                                }
                                                                            }  ?> value="0" name="safety_plan[safe_access_tick]"><label>&#x2717</label>
                                    </td>
                                    <td>
                                        <textarea name="safety_plan[safe_access]">{{ $safety!=null ? $safety->safe_access : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1.2</td>
                                    <td>
                                        Have you read the site hazards board?
                                    </td>
                                    <td class="paid-t">
                                        <input type="radio" value="1" <?php if (!empty($safety)) {
                                                                            if ($safety->site_board_tick == '1') {
                                                                                echo "checked";
                                                                            }
                                                                        }  ?> name="safety_plan[site_board_tick]"><label>&#10004</label>
                                        <input class="marg-l" type="radio" value="0" <?php if (!empty($safety)) {
                                                                                            if ($safety->site_board_tick == '0') {
                                                                                                echo "checked";
                                                                                            }
                                                                                        }  ?> name="safety_plan[site_board_tick]"><label>&#x2717</label>
                                    </td>
                                    <td>
                                        <textarea name="safety_plan[site_board]">{{ $safety!=null ? $safety->site_board : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1.3</td>
                                    <td>
                                        Do you have adequate PPE? Hi vis / steel caps
                                    </td>
                                    <td class="paid-t">
                                        <input type="radio" value="1" <?php if (!empty($safety)) {
                                                                            if ($safety->ppe_tick == '1') {
                                                                                echo "checked";
                                                                            }
                                                                        }  ?> name="safety_plan[ppe_tick]"><label>&#10004</label>
                                        <input class="marg-l" type="radio" value="0" <?php if (!empty($safety)) {
                                                                                            if ($safety->ppe_tick == '0') {
                                                                                                echo "checked";
                                                                                            }
                                                                                        }  ?> name="safety_plan[ppe_tick]"><label>&#x2717</label>
                                    </td>
                                    <td>
                                        <textarea name="safety_plan[ppe]">{{ $safety!=null ? $safety->ppe : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1.4</td>
                                    <td>
                                        Have you completed the Client safety documentation on site?
                                    </td>
                                    <td class="paid-t">
                                        <input type="radio" value="1" <?php if (!empty($safety)) {
                                                                            if ($safety->safety_documentation_tick == '1') {
                                                                                echo "checked";
                                                                            }
                                                                        }  ?> name="safety_plan[safety_documentation_tick]"><label>&#10004</label>
                                        <input class="marg-l" type="radio" value="0" <?php if (!empty($safety)) {
                                                                                            if ($safety->safety_documentation_tick == '0') {
                                                                                                echo "checked";
                                                                                            }
                                                                                        }  ?> name="safety_plan[safety_documentation_tick]"><label>&#x2717</label>
                                    </td>
                                    <td>
                                        <textarea name="safety_plan[safety_documentation]">{{ $safety!=null ? $safety->safety_documentation : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1.5</td>
                                    <td>
                                        Are there others on site we need to communicate with?
                                    </td>
                                    <td class="paid-t">
                                        <input type="radio" value="1" <?php if (!empty($safety)) {
                                                                            if ($safety->communicate_tick == '1') {
                                                                                echo "checked";
                                                                            }
                                                                        }  ?> name="safety_plan[communicate_tick]"><label>&#10004</label>
                                        <input class="marg-l" type="radio" value="0" <?php if (!empty($safety)) {
                                                                                            if ($safety->communicate_tick == '0') {
                                                                                                echo "checked";
                                                                                            }
                                                                                        }  ?> name="safety_plan[communicate_tick]"><label>&#x2717</label>
                                    </td>
                                    <td>
                                        <textarea name="safety_plan[communicate]">{{ $safety!=null ? $safety->communicate : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1.6</td>
                                    <td>
                                        Is the site tidy and clear for you work activity?
                                    </td>
                                    <td class="paid-t">
                                        <input type="radio" value="1" <?php if (!empty($safety)) {
                                                                            if ($safety->work_activity_tick == '1') {
                                                                                echo "checked";
                                                                            }
                                                                        }  ?> name="safety_plan[work_activity_tick]"><label>&#10004</label>
                                        <input class="marg-l" type="radio" value="0" <?php if (!empty($safety)) {
                                                                                            if ($safety->work_activity_tick == '0') {
                                                                                                echo "checked";
                                                                                            }
                                                                                        }  ?> name="safety_plan[work_activity_tick]"><label>&#x2717</label>
                                    </td>
                                    <td>
                                        <textarea name="safety_plan[work_activity]">{{ $safety!=null ? $safety->work_activity : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1.7</td>
                                    <td>
                                        Is the site secure, i.e. fenced / gate closed?
                                    </td>
                                    <td class="paid-t">
                                        <input type="radio" value="1" <?php if (!empty($safety)) {
                                                                            if ($safety->gate_closed_tick == '1') {
                                                                                echo "checked";
                                                                            }
                                                                        }  ?> name="safety_plan[gate_closed_tick]"><label>&#10004</label>
                                        <input class="marg-l" type="radio" value="0" <?php if (!empty($safety)) {
                                                                                            if ($safety->gate_closed_tick == '0') {
                                                                                                echo "checked";
                                                                                            }
                                                                                        }  ?> name="safety_plan[gate_closed_tick]"><label>&#x2717</label>
                                    </td>
                                    <td>
                                        <textarea name="safety_plan[gate_closed]">{{ $safety!=null ? $safety->gate_closed : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1.8</td>
                                    <td>
                                        Are site hazards adequately controlled?
                                    </td>
                                    <td class="paid-t">
                                        <input type="radio" value="1" <?php if (!empty($safety)) {
                                                                            if ($safety->hazard_controlled_tick == '1') {
                                                                                echo "checked";
                                                                            }
                                                                        }  ?> name="safety_plan[hazard_controlled_tick]"><label>&#10004</label>
                                        <input class="marg-l" type="radio" value="0" <?php if (!empty($safety)) {
                                                                                            if ($safety->hazard_controlled_tick == '0') {
                                                                                                echo "checked";
                                                                                            }
                                                                                        }  ?> name="safety_plan[hazard_controlled_tick]"><label>&#x2717</label>
                                    </td>
                                    <td>
                                        <textarea name="safety_plan[hazard_controlled]">{{ $safety!=null ? $safety->hazard_controlled : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1.9</td>
                                    <td>
                                        Do you have access to Power / Water / Toilet?
                                    </td>
                                    <td class="paid-t">
                                        <input type="radio" value="1" <?php if (!empty($safety)) {
                                                                            if ($safety->power_access_tick == '1') {
                                                                                echo "checked";
                                                                            }
                                                                        }  ?> name="safety_plan[power_access_tick]"><label>&#10004</label>
                                        <input class="marg-l" type="radio" value="0" <?php if (!empty($safety)) {
                                                                                            if ($safety->power_access_tick == '0') {
                                                                                                echo "checked";
                                                                                            }
                                                                                        }  ?> name="safety_plan[power_access_tick]"><label>&#x2717</label>
                                    </td>
                                    <td>
                                        <textarea name="safety_plan[power_access]">{{ $safety!=null ? $safety->power_access : '' }}</textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="8" style="background-color:#c9ced6;">3.0 JOB SAFETY ANALYSIS AND HAZARD MANAGEMENT
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2" scope="colgroup">&#10004 JOB STEP
                                    </th>
                                    <th>RISK IDENTIFIED
                                    </th>
                                    <th>HAZARD CONTROL METHOD
                                        E Eliminate / M - Minimise
                                    </th>
                                    <th>
                                        &#10004
                                    </th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        Foundation
                                        Install / Strip
                                    </td>
                                    <td>
                                        Power Tools
                                    </td>
                                    <td>
                                        M Ensure all electrical is tagged and made safeM - Check guards are in place
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" <?php if (!empty($safety)) {
                                                                                if ($safety->foundation == '1') {
                                                                                    echo "checked";
                                                                                }
                                                                            }  ?> name="safety_plan[foundation]">
                                    </td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                        Noise
                                    </td>
                                    <td>
                                        M Ear muffs to be worn
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" <?php if (!empty($safety)) {
                                                                                if ($safety->noise == '1') {
                                                                                    echo "checked";
                                                                                }
                                                                            }  ?> name="safety_plan[noise]">
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                        Dust
                                    </td>
                                    <td>
                                        M Dust masks to be worn
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" name="safety_plan[dust]" <?php if (!empty($safety)) {
                                                                                                        if ($safety->dust == '1') {
                                                                                                            echo "checked";
                                                                                                        }
                                                                                                    }  ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                        Hit by Plant
                                    </td>
                                    <td>
                                        M Hi Vis to be worn
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" name="safety_plan[hit_plant]" <?php if (!empty($safety)) {
                                                                                                            if ($safety->hit_plant == '1') {
                                                                                                                echo "checked";
                                                                                                            }
                                                                                                        }  ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                        Poor Housekeeping
                                    </td>
                                    <td>
                                        M Keep the site tidy, stack materials in designated areas
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" name="safety_plan[poor_housekeeping]" <?php if (!empty($safety)) {
                                                                                                                    if ($safety->poor_housekeeping == '1') {
                                                                                                                        echo "checked";
                                                                                                                    }
                                                                                                                }  ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                        Exposed Steel
                                    </td>
                                    <td>
                                        M Ensure exposed steel is identified / capped
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" name="safety_plan[exposed_steel]" <?php if (!empty($safety)) {
                                                                                                                if ($safety->exposed_steel == '1') {
                                                                                                                    echo "checked";
                                                                                                                }
                                                                                                            }  ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                        Loose Materials
                                    </td>
                                    <td>
                                        M Ensure materials are secured
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" name="safety_plan[loose_material]" <?php if (!empty($safety)) {
                                                                                                                if ($safety->loose_material == '1') {
                                                                                                                    echo "checked";
                                                                                                                }
                                                                                                            }  ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                        Services
                                    </td>
                                    <td>
                                        M Check for overhead and underground services
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" name="safety_plan[services]" <?php if (!empty($safety)) {
                                                                                                            if ($safety->services == '1') {
                                                                                                                echo "checked";
                                                                                                            }
                                                                                                        }  ?>>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p>All personnel and visitors have been shown and advised of all of the hazards and controls identified.
                            All workers must be involved in completing this Site Safety Plan. All persons signed below fully
                            understand and acknowledge their requirements</p>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="6" style="text-align:center;background-color:#c9ced6;">SIGN IN / INDUCTION</th>
                                </tr>
                            </thead>
                            <tbody id="induction_body">
                                <tr>
                                    <th scope="row">Date
                                    </th>
                                    <th>Name
                                    </th>
                                    <th>Signature
                                    </th>
                                </tr>
                                @if(!empty($safety))
                                @for($key=1;$key<=count($safety->induction_date);$key++)
                                <tr>
                                    <td scope="row">
                                        <input type="date"  readonly value="{{ $safety!=null ? $safety->induction_date['date'.$key] : '' }}" name="safety_plan[induction_date][date{{$key}}]">
                                    </td>
                                    <td>
                                        <input type="text" readonly value="{{ $safety!=null ? $safety->induction_name['name'.$key] : '' }}" name="safety_plan[induction_name][name{{$key}}]">
                                    </td>
                                    <td>
                                        @if(!empty($safety->sign['sign'.$key]))
                                        <img src="{{$safety->sign['sign'.$key]}}" id="induction_sign{{$key}}" width="200">
                                        @else
                                        <canvas id="induction_canvas{{$key}}" style="border: 1px solid black;"></canvas>
                                        <button type="button" data-id="indunction_signaturePad{{$key}}" class="btn btn-sm clear" style="color:#fff;background-color:#172b4d">Clear</button>
                                        @endif
                                    </td>
                                </tr>
                                @endfor
                                @endif
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2"><button type="button" onclick="addsignaturepad();" class="btn" style="color:#fff;background-color:#172b4d">Add Signaturepad</button></div>
                        </div>   
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-3" style="display: none;">
                                @if(!empty($safety->foreman_sign))
                                <img src="{{$safety->foreman_sign}}" id="safetyplan_sign" width="200">
                                @else
                                <canvas id="safetyplan_canvas" style="border: 1px solid black;"></canvas>
                                <button type="button" data-id="safetyplan_signature" class="btn btn-sm clear" style="color:#fff;background-color:#172b4d">Clear</button>
                                @endif
                            </div>
                            <div class="col-md-1"></div>
                            <div style="float:right"><button type="submit" class="btn btn-secondary">Save</button>

                </form>
            </div>
        </div>
    </div>
</div>
</div>

</div>

</div>
</div>
<style>
  .tooltip-inner {
    color: #172B4D;
    background-color: #ffffff;
    border: 1px solid #172B4D;
}
</style>
<script>


    $(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
     
    var i="<?php if(!empty($safety->induction_date)){echo count($safety->induction_date)+1;}else {echo 1; } ?>";
    function addsignaturepad()
    {
        var html='<tr><td scope="row"><input type="date" required value="" name="safety_plan[induction_date][date'+i+']"></td><td><input type="text" required value="" name="safety_plan[induction_name][name'+i+']"></td><td><canvas id="induction_canvas'+i+'" style="border: 1px solid black;"></canvas><button type="button" data-id="indunction_signaturePad'+i+'" class="btn btn-sm clear" style="color:#fff;background-color:#172b4d">Clear</button></td></tr>';
        $("#induction_body").append(html);
        window["indunction_signaturePad" + i]=new SignaturePad($("#induction_canvas"+i+"")[0]);
        console.log(indunction_signaturePad1);
        i++;
    }
    $(document).on("click", "#back", function() {
        var id = $(this).data('id');

        jQuery.ajax({
            url: "{{ url('/check-list') }}",
            method: 'get',
            success: function(result) {
                var ele = $(result);

                jQuery('.container .main').html(ele.find(".container .main").html());
            }
        });
    })
    $(".project_status").on("change", function() {
        var status = $(this).val();
        var status_label_id = $(this).data('id');
        var project_id = $(this).data('project');
        
        if(!(status_label_id==10 && status==0))
        {
        Swal.fire({
            title: "Are you sure you want to change the status?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Yes',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            cancelButtonText: 'No',
            dangerMode: true,
        }).then(function(result) {
            if (result.isConfirmed) {

                jQuery.ajax({
                    url: "{{ url('/change-project-status') }}",
                    method: 'post',
                    data: {
                        project_id: project_id,
                        status: status,
                        status_label_id: status_label_id
                    },
                    success: function(result) {
                        Toast.fire({
                            icon: 'success',
                            title: "Status changed successfuly."
                        }).then(function(result) {
                            window.location.reload();
                        });
                    }
                });
            }
        })
        }else
        {
            
            $("#reason_form").modal('show');
        }
    })

    $("#submit_reason").click(function(){
        var project_id=$(this).data('project');
        var reason=$("#reason").val();

        jQuery.ajax({
                    url: "{{ url('/change-project-status') }}",
                    method: 'post',
                    data: {
                        project_id: project_id,
                        status: 0,
                        status_label_id: 10,
                        reason:reason
                    },
                    success: function(result) {
                        Toast.fire({
                            icon: 'success',
                            title: "Status changed successfuly."
                        }).then(function(result) {
                            window.location.reload();
                        });
                    }
                });
    });

    if ($('#markout_canvas').length) {
        var markout_signature = new SignaturePad($("#markout_canvas")[0]);
    }
    if ($('#drawin_canvas').length) {
        var drawin_signature = new SignaturePad($("#drawin_canvas")[0]);
    }
    if ($('#onsite_canvas').length) {
        var onsite_signature = new SignaturePad($("#onsite_canvas")[0]);
    }
    if ($('#safetyplan_canvas').length) {
        var safetyplan_signature = new SignaturePad($("#safetyplan_canvas")[0]);
    }

    $(document).on('click', '.clear', function() {
        var id = $(this).data('id');
        var pad = eval(id);
        pad.clear();
    });


    $(document).on('submit', '#qa_form', function(e) {
        if ($('#onsite_canvas').length > 0) {

            var signaturePad = eval("onsite_signature");
            if (!signaturePad.isEmpty()) {
                var image_str = signaturePad.toDataURL();
                $("<input />").attr("type", "hidden")
                    .attr("name", "onsite_sign")
                    .attr("value", image_str).appendTo("#qa_form");
            } else {
                $("<input />").attr("type", "hidden")
                    .attr("name", "onsite_sign")
                    .attr("value", "").appendTo("#qa_form");
            }
        } else {
            $("<input />").attr("type", "hidden")
                .attr("name", "onsite_sign")
                .attr("value", $("#markout_sign").attr("src")).appendTo("#qa_form");
        }
    });

    $(document).on('submit', '#markout_form', function(e) {
        if ($('#markout_canvas').length > 0) {

            var signaturePad = eval("markout_signature");
            if (!signaturePad.isEmpty()) {
                var image_str = signaturePad.toDataURL();
                $("<input />").attr("type", "hidden")
                    .attr("name", "markout_data[foreman_sign]")
                    .attr("value", image_str).appendTo("#markout_form");
            } else {
                $("<input />").attr("type", "hidden")
                    .attr("name", "markout_data[foreman_sign]")
                    .attr("value", "").appendTo("#markout_form");
            }
        } else {
            $("<input />").attr("type", "hidden")
                .attr("name", "markout_data[foreman_sign]")
                .attr("value", $("#markout_sign").attr("src")).appendTo("#markout_form");
        }
        if ($('#drawin_canvas').length > 0) {

            var signaturePad = eval("drawin_signature");
            if (!signaturePad.isEmpty()) {
                var image_str = signaturePad.toDataURL();
                $("<input />").attr("type", "hidden")
                    .attr("name", "markout_data[draw_in]")
                    .attr("value", image_str).appendTo("#markout_form");
            } else {
                $("<input />").attr("type", "hidden")
                    .attr("name", "markout_data[draw_in]")
                    .attr("value", "").appendTo("#markout_form");
            }
        } else {
            $("<input />").attr("type", "hidden")
                .attr("name", "markout_data[draw_in]")
                .attr("value", $("#drawin_sign").attr("src")).appendTo("#markout_form");
        }
    });

    $(document).on('submit', '#safety_form', function(e) {
        for (var i = 1; i <= 5; i++) {
            if ($('#induction_canvas' + i).length > 0) {
                var signaturePad = eval("indunction_signaturePad" + i);
                if (!signaturePad.isEmpty()) {
                    var image_str = signaturePad.toDataURL();
                    $("<input />").attr("type", "hidden")
                        .attr("name", "safety_plan[sign][sign" + i + "]")
                        .attr("value", image_str).appendTo("#safety_form");
                } else {
                    $("<input />").attr("type", "hidden")
                        .attr("name", "safety_plan[sign][sign" + i + "]")
                        .attr("value", "").appendTo("#safety_form");
                }
            } else {
                $("<input />").attr("type", "hidden")
                    .attr("name", "safety_plan[sign][sign" + i + "]")
                    .attr("value", $("#induction_sign" + i).attr("src")).appendTo("#safety_form");
            }
        }
        if ($('#safetyplan_canvas').length > 0) {

            var signaturePad = eval("safetyplan_signature");
            if (!signaturePad.isEmpty()) {
                var image_str = signaturePad.toDataURL();
                $("<input />").attr("type", "hidden")
                    .attr("name", "safety_plan[foreman_sign]")
                    .attr("value", image_str).appendTo("#safety_form");
            } else {
                $("<input />").attr("type", "hidden")
                    .attr("name", "safety_plan[foreman_sign]")
                    .attr("value", "").appendTo("#safety_form");
            }
        } else {
            $("<input />").attr("type", "hidden")
                .attr("name", "safety_plan[foreman_sign]")
                .attr("value", $("#safetyplan_sign").attr("src")).appendTo("#safety_form");
        }
    });
</script>