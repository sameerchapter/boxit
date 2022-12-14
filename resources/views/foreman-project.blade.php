@extends('layouts.app')

@section('content')
<div id="content ">
			<div class="container main">
        <div class="card-new ptb-50 mob-full-w paid-l-r">
				<div class="row">
					<div class="col-md-12">
						<div class="form-head">
				      <span>Projects</span>
			      </div>
					</div>
				</div>
				@foreach($projects as $project)
        <div class="row mob-full-w">
          <div class="col-md-12">
         <div class="bdr-btm project-flex ptb-25">
          <div class="p-name">
            <span>{{$project->address}}</span>
          </div>
          <div class="v-name details" data-id="{{$project->id}}">
            <span>View</span>
            <img src="img/edit.png">
          </div>
        </div>
          </div>
        </div>
       @endforeach
        
			</div>
			</div>
 
		</div>
    <script>
      $(document).on("click",".details",function(){
        var id=$(this).data('id');
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
               jQuery.ajax({
                  url: "{{ url('/foreman-single-project') }}",
                  method: 'post',
                  data: {
                     id: id,
                  },
                  success: function(result){
                     jQuery('.main').html(result);
                  }});      })
      </script>
@endsection