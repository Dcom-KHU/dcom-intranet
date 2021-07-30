@extends('layouts.app')

@section('content')
<div class="col-md-8 col-md-offset-2">
	<div class="form-group">
		<div class="col-md-12">
			@foreach ($boards as $board)
				<a href="/notice/{{$board->id}}" style="text-decoration: none;">
					<div class="panel panel-success group-list">
						<div class="panel-heading">
							
							{{$board->title}}
							
						</div>
						<div class="panel-body" style="color:#333; line-height:10px;">
							<span style="float:left;">
								{{$board->created_at->format('Y-m-d')}}
							</span>
							<span style="float:right;">
								<i class="fa fa-eye" aria-hidden="true"></i>&nbsp;&nbsp;{{$board->viewer}}
							</span>
						</div>
					</div>
				</a>
			@endforeach
		</div>
	</div>
</div>	
					
				
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
			
		<div class="form-group">
			<ul class="pagination" style="margin-left:30px; margin-top:10px;">
				@for ($i = 1; $i <= $boards->lastPage(); $i++)
					<li><a href="?page={{$i}}">{{$i}}</a></li>
				@endfor
			</ul>
			@if(Auth::user()->admin == 1)
				<div style="display:inline; float:right; margin-right:30px; margin-top:10px;">
					<a href="/notice/write" class="btn btn-success" role="button">글쓰기</a>
				</div>
			@endif
			
		</div>
		
		<div style="margin: 20px 0; width: 100%;">
			<div style="float:left; margin: 0 10px;">
				<select class="selectpicker" data-style="btn-success" data-width="fit" name="search">
					<option>제목</option>
					<option>내용</option>
					<option>제목+내용</option>
				</select>
			</div>
			<div style="float:right; margin: 0 10px;">
				<button type="submit" class="btn btn-success" onclick="search('free')">검색</button>
			</div>
			<div style="display: block; overflow: auto;">
				<input id="searchtext" type="text" class="form-control" name="searchtext" value="{{ old('searchtext') }}" required style="padding-top:4px;">
			</div>
		</div>
			
	</div>
</div>

@endsection