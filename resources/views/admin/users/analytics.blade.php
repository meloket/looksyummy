@extends('admin.users.base')
@section('action-content')

<!-- Main content -->
<section class="content">
  <div class="box">
  <div class="box-header">
    <div class="row">
        <div class="col-sm-10">
          <h3 class="box-title">Analytics</h3>
        </div>
        <div class="col-sm-2">
          <a class="btn btn-primary" href="/admin/analytics/export/{{ $id }}">Export</a>
        </div>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
	<div class="row">
		<div class="col-sm-6"></div>
		<div class="col-sm-6"></div>
	</div>
	
	<div class="callout callout-warning">
    <h4>Coming Soon!</h4>

    <p>You can plot information from 2 or more restaurants together into this graph.</p>
  </div>
  <div class="row">
    <div class="col-md-6">
      <!-- AREA CHART -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Area Chart</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body chart-responsive">
          <div class="chart" id="revenue-chart" style="height: 300px;"></div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

      <!-- DONUT CHART -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Donut Chart</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body chart-responsive">
          <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </div>
    <!-- /.col (LEFT) -->
    <div class="col-md-6">
      <!-- LINE CHART -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Line Chart</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body chart-responsive">
          <div class="chart" id="line-chart" style="height: 300px;"></div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

      <!-- BAR CHART -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Bar Chart</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body chart-responsive">
          <div class="chart" id="bar-chart" style="height: 300px;"></div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </div>
    <!-- /.col (RIGHT) -->
  </div>
  <!-- /.row -->

  
<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">




  <div class="row">

    <div class="col-sm-12">
<h4>Likes</h4>
      <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
        <thead>
          <tr role="row">
            
            <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Meal Photo">Photo</th>
            <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Meal Title">Meal Title</th>
    				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Meal Likes Count">Likes Count</th>
            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="2" aria-label="Action: activate to sort column ascending">Action</th>
          </tr>
        </thead>
        <tbody>
  			@if(isset($meals1))
            
        
              @foreach ($meals1 as $meal)
                  <tr role="row" class="odd">
                    <td width="15%">
                        <img class="img-responsive" alt="" src="{{ asset(env('MEAL_PIC_MAX', '/storage/photos/thumb/').$meal->photo) }}" />
                    </td>
                    
                    <td width="15%">{{ $meal->title }}</td>
                    <td width="15%">
				@isset($meal['likesCount'][0]) {{ $meal['likesCount'][0]['aggregate'] }} @endisset</td>
                    <td>
				@isset($meal['likesCount'][0])
                     <a href="/admin/meals/details/likes/{{ $meal->id }}">Show Details</a> 
				@endisset
                    </td>

                </tr>
              @endforeach
  			@endif
        </tbody>
        <!--<tfoot>
          <tr>
            <th width="20%" rowspan="1" colspan="1">Meals</th>
            <th rowspan="1" colspan="2">Action</th>
          </tr>
        </tfoot>-->
      </table>
    </div>
  </div>

	  
	  <div class="row">
	  
        <div class="col-sm-12">
		<h4>Comments</h4>
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Meal Photo">Photo</th>
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Meal Title">Meal Title</th>
        				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Meal Likes Count">Comments Count</th>
                <th tabindex="0" aria-controls="example2" rowspan="1" colspan="2" aria-label="Action: activate to sort column ascending">Action</th>
              </tr>
            </thead>
            <tbody>
      			@if(isset($meals2))
                
                  @foreach ($meals2 as $meal)
                      <tr role="row" class="odd">
                        <td width="15%">
                            <img class="img-responsive" alt="" src="{{ asset(env('MEAL_PIC_MAX', '/storage/photos/thumb/').$meal->photo) }}" />
                        </td>
                        
                        <td width="15%">{{ $meal->title }}</td>
                        <td width="15%">
						@isset($meal['commentsCount'][0]) {{ $meal['commentsCount'][0]['aggregate'] }} @endisset</td>
                        <td>
						@isset($meal['commentsCount'][0])
                         <a href="/admin/meals/details/comments/{{ $meal->id }}">Show Details</a>
						@endisset
                        </td>

                    </tr>
                  @endforeach
      			@endif
            </tbody>
            <!--<tfoot>
              <tr>
                <th width="20%" rowspan="1" colspan="1">Meals</th>
                <th rowspan="1" colspan="2">Action</th>
              </tr>
            </tfoot>-->
          </table>
        </div>
      </div>
      
    </div>
  </div>
  <!-- /.box-body -->
</section>
<!-- /.content -->



	
@endsection
